<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class OrderForm extends CFormModel
{
    public $name;
    public $email;
    public $phone;
    public $code;
    public $address;
    public $orders;
    public $detail_text;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('name, phone, code,  email', 'required'),
            // when in register scenario, password must match password2
            array('name, phone, address, detail_text, orders', 'safe'),
        );
    }
    public function save($cart)
    {
        if(!$cart->getPositions())
        return false;

        $table='';
        $total=$cart->getCost();
        #Check User
        $user = User::model()->findByAttributes(array(
            'email' => $this->email
        ));

        #Create User
        if(!isset($user)){
            $password=User::randomPassword();
            $user = new User;
            $user->email=$_POST['email'];
            $user->name=$_POST['name'];
            $user->phone=$_POST['code'].' '.$_POST['phone'];
            $user->delivery_addr=$_POST['address'];
            $user->display_name=$_POST['email'];
            $user->password=$password;
            $user->save();
        }

        #Create Order
        $orderModel=new Order();
        $orderModel->user_id=$user->id;
        $orderModel->phone=$this->code.' '.$this->phone;
        $orderModel->delivery_addr=$this->address;
        $orderModel->comment=$this->detail_text;
        $orderModel->total=$total;
        $orderModel->save();

        if($orderModel->id){
            $cnt=1;
            foreach($this->orders as $order){
                $orderProduct=new OrderProduct();
                $orderProduct->quantity=$order->quantity;
                $orderProduct->order_id=$orderModel->id;
                $orderProduct->product_id=$order->id;
                $orderProduct->save();
                $table.='<tr><td>'.$cnt.'</td><td>'.$order->title.'</td><td>'.$order->quantity.'</td><td>'.$order->price.'</td><td>'.$order->price*$order->quantity.' грн</td></tr>';
                $cnt++;
            }

            #Сообщение покупателю
                $message = new YiiMailMessage;
                $message_body='
                Вітаємо!<br><br>
                Дякуємо за Ваше замовлення на сайті '.$_SERVER['HTTP_HOST'].'.<br><br>
                Ваше замовлення №'.$orderModel->id.':<br><br>
                <table border="1">
                <tr><td>№</td><td>Назва</td><td>Кількість</td><td>Ціна за шт.</td><td>Ціна всього</td></tr>
                '.$table.'
                <tr><td colspan="3"></td><td>Всього:</td><td>'.$total.' грн</td></tr>
                </table><br><br>
                Ваші дані:<br><br>
                Ім&#769;я: '.$user->name.'<br>
                Телефон: '.$user->phone.'<br>
                Адреса доставки: '.$user->address.'<br>

                Протягом 10 хвилин наш менеджер звяжеться з Вамы<br><br>

                Наш телефон: '.Option::getOpt('mainphone').'<br><br>
                З повагою,<br>
                Салатник';
                $message->setBody($message_body, 'text/html');
                $message->subject = 'Салатник отримав ваше замовлення';
                $message->addTo($user->email);
                $message->from = array(Yii::app()->params['adminEmail']=>'Салатник');
                Yii::app()->mail->send($message);
            #Сообщение менеджерам
                $message = new YiiMailMessage;

                $managermessage='Здравствуйте!<br><br>
                Поступил новый заказ №'.$orderModel->id.' от пользователя '.$user->email.'. Новая сумма заказа '.$total.' грн.<br><br>
                <table border="1">
                <tr><td>№</td><td>Название</td><td>Количество</td><td>Цена за шт.</td><td>Цена всего</td></tr>
                '.$table.'
                <tr><td colspan="3"></td><td>Всего:</td><td>'.$total.' грн</td></tr>';
                $managermessage.='</table>
                <br><br>
                Данные заказчика:<br><br>
                Имя: '.$this->name.'<br>
                Телефон: '.$this->phone.'<br>
                Адрес: '.$this->address.'<br>
                Для просмотра заказа зайдите в панель упровления и просмотрите <a href="http://'.$_SERVER['HTTP_HOST'].'/backend.php?r=order/update&id='.$orderModel->id.'">заказ</a>.
                ';
                $message->setBody($managermessage,'text/html');
                $message->subject = 'Новый заказ';
                $orderuserarr=explode(',',Option::getOpt('order_emails'));
                foreach($orderuserarr as $order_u)
                    $message->addTo(trim($order_u));
                $message->from = array(Yii::app()->params['adminEmail']=>'Салатник');
                Yii::app()->mail->send($message);


        }
       return $orderModel;
    }
}
