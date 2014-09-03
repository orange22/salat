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
            array('name, phone, email', 'required'),
            // when in register scenario, password must match password2
            array('name, phone, address, detail_text, orders', 'safe'),
        );
    }
    public function save()
    {
        $user = User::model()->findByAttributes(array(
            'email' => $this->email
        ));
        if(isset($user)){

        }else{
            $password=User::randomPassword();
            $user = new User;
            $user->email=$_POST['email'];
            $user->name=$_POST['name'];
            $user->phone=$_POST['phone'];
            $user->delivery_addr=$_POST['address'];
            $user->display_name=$_POST['email'];
            $user->password=$password;
            $user->save();
        }

        $orderModel=new Order();
        $orderModel->user_id=$user->id;
        $orderModel->phone=$this->phone;
        $orderModel->delivery_addr=$this->address;
        $orderModel->comment=$this->detail_text;
        $orderModel->save();

        if($orderModel->id){
            foreach($this->orders as $order){
                $orderProduct=new OrderProduct();
                $orderProduct->quantity=$order->quantity;
                $orderProduct->order_id=$orderModel->id;
                $orderProduct->product_id=$order->id;
                $orderProduct->save();
            }
        }
       return $orderModel;
    }
}
