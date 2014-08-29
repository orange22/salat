<?php
class OrderController extends BackController
{
	public function init()
    {
    	parent::init();
    	Yii::import('common.extensions.yii-mail.*');
    }	
	/*
	protected function beforeAction($action)
		{
			if(isset($_POST['OrderDish']))
			{
				$OrderDishData = $_POST['OrderDish'];
				foreach($OrderDishData)
			}    	
			return parent::beforeAction($action);
		}	*/
	/*
		
	*/
		
	public function actionAdmin()
    {
        /** @var $model BaseActiveRecord */
        $model = $this->getNewModel('search');
        
        
        $model->unsetAttributes(); // clear any default values
        if(isset($_GET[$this->getModelName()]))
            $model->attributes = $_GET[$this->getModelName()];

        $model->restoreGridState();
        
        $this->render($this->view['admin'], array(
            'model' => $model,
        ));
    }	
	
    public function actionCreate()
    {
        /** @var $model BaseActiveRecord */
        $model = $this->getNewModel();

        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['create'], array(
            'model' => $model,
        ));
    }
    
    protected function doAction($model)
    {
        $action = $this->action->getId();

        if(isset($_POST[$this->getModelName()]))
        {
            $postData = $_POST[$this->getModelName()];
            
            if($postData['user_id']<1){
                
                if($newuser=User::model()->findByAttributes(array('email'=>$postData['phone'].'@lpovar.com.ua')))
                    $postData['user_id']=$newuser->id;
                else{
                    $newuser=new User;
                    $newuser->email=$postData['phone'].'@lpovar.com.ua';
                    $newuser->phone=$postData['phone'];
                    $newuser->name=$postData['name'];
                    $newuser->delivery_addr=$postData['delivery_addr'];
                    $newuser->delivery_from=$postData['delivery_from'];
                    $newuser->delivery_till=$postData['delivery_till'];
                    $newuser->save();
                    $postData['user_id']=$newuser->id;
                }
                
            }
            //print_r($postData);
            
            /** @var $uploadModel FileAttachBehavior */
            $uploadModel = $this->getNewModel('upload')->asa('attach');
            $uploads = $uploadModel ? $uploadModel->saveUploads($postData) : null;

            $model->attributes = $postData;

            if(in_array($action, array('clone')))
            {
                $model->unsetAttributes(array('id'));
                $model->setIsNewRecord(true);
            }

            $model->setAttributes($uploads);
            $model->validate();

            if($uploadModel)
                $uploadModel->copyErrors($model);

            if(!$model->hasErrors() && $model->save(false))
                return $this->afterActionDone($model);

            Yii::app()->user->addFlash(
                'error',
                $this->renderPartial('//inc/_model_errors', array('data' => $model->stringifyAttributeErrors()), true)
            );
        }

        return false;
    }
    
    public function actionUpdate($id)
    {
        /** @var $model BaseActiveRecord */
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['update'], array(
            'model' => $model,
        ));
    }
        
    	
	protected function afterActionDone($model)
    {
		$user=User::model()->findByPk($model->user_id);	
		/*
		if(isset($_POST['Order']['orderstate_id'])){
					if($_POST['Order']['orderstate_id']>2 && $model->mail_open<1){
							$message = new YiiMailMessage;
							$message_body='
							Р’РёРґРµРѕСЂРµС†РµРїС‚ Р›РёС‡РЅРѕРіРѕ РџРѕРІР°СЂР°<br><br>
							РњС‹ С…РѕС‚РёРј, С‡С‚РѕР±С‹ Сѓ РІР°СЃ С‚РѕС‡РЅРѕ РІСЃРµ РїРѕР»СѓС‡РёР»РѕСЃСЊ, РїРѕСЌС‚РѕРјСѓ РІРёРґРµРѕСЂРµС†РµРїС‚ РїСЂРёРіРѕС‚РѕРІР»РµРЅРёСЏ Р·Р°РєР°Р·Р°РЅРѕРіРѕ РІР°РјРё РЅР°Р±РѕСЂР° СѓР¶Рµ РґРѕСЃС‚СѓРїРµРЅ РІ РІР°С€РµРј РєР°Р±РёРЅРµС‚Рµ, РЅР° СЃР°Р№С‚Рµ.
							Р’С‹ РјРѕР¶РµС‚Рµ РїРµСЂРµР№С‚Рё РїРѕ СЃСЃС‹Р»РєРµ: http://lpovar.com.ua/cabinet/<br><br>
							РЎ СѓРІР°Р¶РµРЅРёРµРј,<br>
							Р›РёС‡РЅС‹Р№ РџРѕРІР°СЂ';
							$message->setBody($message_body, 'text/html');
							$message->subject = 'Р’РёРґРµРѕСЂРµС†РµРїС‚ Р›РёС‡РЅРѕРіРѕ РџРѕРІР°СЂР°';
							$message->addTo($user->email);
							$message->from = array(Yii::app()->params['adminEmail']=>'Р›РёС‡РЅС‹Р№ РџРѕРІР°СЂ');
							Yii::app()->mail->send($message);
							$model->mail_open=1;
							$model->save();
					}
				}	*/
		
			
		if(isset($_POST['OrderDish']))
        {
            $hasErrors = false;
            $psModel = new OrderDish();
            $OrderDishData = $_POST['OrderDish'];
            foreach($OrderDishData as $idx => $item)
            {
                if($item['dish_id']>0 && $item['quantity']>0){
					
	                $psModel->dish_id = $item['dish_id'];
	                $psModel->quantity = $item['quantity'];
	
	                if(!$psModel->validate(array('dish_id', 'quantity', )))
	                {
	                    $hasErrors = true;
	                    user()->addFlash(
	                        'error',
	                        $this->renderPartial('//inc/_model_errors', array('data' => $psModel->stringifyAttributeErrors()), true)
	                    );
	                    continue;
	                }
                }
               /*
                if(!(int)$item['dish_id'] && !(float)$item['quantity'] )
                                   unset($OrderDishData[$idx]);*/
               
            }

          	 if(!$hasErrors)
                OrderDish::model()->updateForOrder($model->id, $OrderDishData); 
        }else{
        	 OrderDish::model()->updateForOrder($model->id, array());
        }
		
		if(isset($_POST['OrderDrink']))
        {
            $hasErrors = false;
            $psModel = new OrderDrink();
            $OrderDrinkData = $_POST['OrderDrink'];
            foreach($OrderDrinkData as $idx => $item)
            {
                	
				 if($item['drink_id']>0 && $item['quantity']>0){	
	                $psModel->drink_id = $item['drink_id'];
	                $psModel->quantity = $item['quantity'];
	
	                if(!$psModel->validate(array('drink_id', 'quantity', )))
	                {
	                    $hasErrors = true;
	                    user()->addFlash(
	                        'error',
	                        $this->renderPartial('//inc/_model_errors', array('data' => $psModel->stringifyAttributeErrors()), true)
	                    );
	                    continue;
	                }
	               /*
	                if(!(int)$item['drink_id'] && !(float)$item['quantity'] )
	                                   unset($OrderDishData[$idx]);*/
				 }
            }

          	 if(!$hasErrors)
                OrderDrink::model()->updateForOrder($model->id, $OrderDrinkData); 
        }else{
        	 OrderDrink::model()->updateForOrder($model->id, array());
        }
		$discount=0;
        if($user->discount>0){
            $discount=$user->discount;
        }elseif($user->discount<1 && $_POST['Order']['discount_id']>0){
            $disc=Discount::model()->findByPk($_POST['Order']['discount_id']);
            if($disc->discount>0)
            $discount=$disc->discount;
        }

        $discountpercent=null;
        if($discount>0){
            $discountpercent=$discount;
            $discount=1-($discount/100);
        }


        if($discountpercent<1)
            $discount=1;


        /*if(isset($_POST['CharityOrder']))
        {
            $charityOrders=CharityOrder::model()->findAllByAttributes(array('order_id'=>$model->id));
            foreach($charityOrders){

            }

        }*/
        if(isset($_POST['CharityOrder']))
        {
            $hasErrors = false;
            $psModel = new CharityOrder();
            $CharityOrderData = $_POST['CharityOrder'];
            foreach($CharityOrderData as $item)
            {
                    $psModel->charity_id = $item;
                    if(!$psModel->validate(array('charity_id')))
                    {
                        $hasErrors = true;
                        user()->addFlash(
                            'error',
                            $this->renderPartial('//inc/_model_errors', array('data' => $psModel->stringifyAttributeErrors()), true)
                        );
                        continue;
                    }

            }

            if(!$hasErrors)
                CharityOrder::model()->updateForOrder($model->id, $CharityOrderData);
        }else{
            CharityOrder::model()->updateForOrder($model->id, array());
        }





		$sql='
			SELECT SUM(total) FROM ((SELECT 
			SUM(gs_order_dish.`quantity`*gs_dish.price)*'.$discount.' AS `total`
			FROM
			  gs_order_dish
			INNER JOIN gs_dish
			ON gs_dish.id=gs_order_dish.`dish_id`
			WHERE gs_order_dish.`order_id`='.$model->id.')
			UNION ALL
			(SELECT SUM(gs_charity.value) AS `total` FROM gs_charity_order INNER JOIN gs_charity ON gs_charity.id=gs_charity_order.charity_id WHERE gs_charity_order.order_id='.$model->id.')
			UNION ALL
			(SELECT 
			SUM(gs_order_drink.`quantity`*gs_drink.price) AS `total`
			FROM
			  gs_order_drink
			INNER JOIN gs_drink
			ON gs_drink.id=gs_order_drink.`drink_id`
			WHERE gs_order_drink.`order_id`='.$model->id.')) t1
			';
			$connection=Yii::app()->db;   // assuming you have configured a "db" connection
			$command=$connection->createCommand($sql);
			//$command->bindParam(":order_id",374,PDO::PARAM_INT);
			$value=$command->queryScalar();
		//	echo $value;
			$order=Order::model()->findByPk($model->id);
			$order->total=$value;
			$order->save();
		//die();		
		
		
        return parent::afterActionDone($model);
    }
public function actionReceipt() {
        $oid=$_GET['id'];
        $pdf = Yii::createComponent('common.extensions.tcpdf.ETcPdf', 
                            'P', 'cm', 'A4', true, 'UTF-8');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("Lpovar");
        $pdf->SetTitle("Товарный чек");
        //$pdf->SetSubject("TCPDF Tutorial");
        $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        //$pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('freeserif', '', 11);

    $tbl='';
       $order=Order::model()->with(array('orderDishes'=>array('with'=>'dish'),'user','orderDrinks'=>array('with'=>'order'),'discount'))->find('t.id=:id',array(':id'=>$oid));
       $user=User::model()->findByPk($order->user_id);   
       $tbl .= '
        <table>
            <tr>
                <td width="125px">
                    <img height="70px" style="float:left;" src="/images/logo_small.jpg"/>
                </td>
                <td><br/><br/><br/>Личный Повар<br/>
                    Lpovar.com.ua<br/>
                    Тел. 044 360 60 09
                </td>
            </tr>
        </table>
        <div align="center"><br/><strong>Товарный чек</strong><br/></div>        
        <table style="padding: 5px;" border="1">
            <tbody>
            <tr>
                <td width="20px">№</td>
                <td width="320px">Наименование</td>
                <td width="30px">Кол</td>
                <td width="70px">Цена</td>
                <td width="70px">Сумма</td>
            </tr>
            ';
       $cnt=1;
       //$total_all=0;
       $total_dish=0;
       $total_drink=0;
       foreach($order->orderDishes as $dish){
       $total=null;
       $total=$dish->dish->price*$dish->quantity;
       $total_dish=$total_dish+$total;
       $tbl .= "
            <tr>
                <td>{$cnt}</td>
                <td>{$dish->dish->title}</td>
                <td>{$dish->quantity}</td>
                <td>{$dish->dish->price} грн.</td>
                <td>{$total} грн.</td>
            </tr>";
       $cnt++;     
       }
       
       foreach($order->orderDrinks as $drink){
       $total=null;
       $total=$drink->drink->price*$drink->quantity;
       $total_drink=$total_drink+$total;
       $tbl .= "
            <tr>
                <td>{$cnt}</td>
                <td>{$drink->drink->title}</td>
                <td>{$drink->quantity}</td>
                <td>{$drink->drink->price} грн.</td>
                <td>{$total} грн.</td>
            </tr>";
       $cnt++;     
       }

       
        $discount=null;
        if($user->discount>0){
            $discount=$user->discount;
        }elseif($order->discount_id>1){
            $disc=Discount::model()->findByPk($order->discount_id);
            if($disc->discount>0)
            $discount=$disc->discount;
        }
        $discountpercent=null;
        if($discount>0){
                    $discountpercent=$discount;
                    $discount=1-($discount/100);
        }
        
    
        if($discountpercent<1)
            $discount=1;
       //echo $discount;
       //die();
       $chartotal=0;
       foreach($order->orderCharities as $char){
           $chartotal=$chartotal+$char->charity->value;
           $tbl .= "
            <tr>
                <td></td>
                <td>{$char->charity->title}</td>
                <td></td>
                <td></td>
                <td>{$char->charity->value} грн.</td>
            </tr>";
       }
       $total_all=$total_dish*$discount+$total_drink+$chartotal;



       if($discountpercent>0)
            $totaltext='Всего (со скидкой -'.$discountpercent.'% на наборы блюд)';
       else{
            $totaltext='Всего';
       }
       if($order->orderstate_id==5)
           $totaltext.=' <b>('.$order->orderstate->title.')</b>';


        $tbl .= '
            <tr>';

        if($total_all>0)
        $tbl .= '
            <td colspan="4" align="right">'.$totaltext.':</td><td>'.$total_all.' грн.</td>';
        else
            $tbl .= '
            <td colspan="4" align="right">'.$totaltext.':</td><td>Бесплатно!</td>';

        $tbl .= '
            </tr>
            </tbody>
        </table>
        <br/><br/>';
        $tbl .= '
        <strong>Доставка:</strong> '.$order->delivery_addr.'<br/>';
        if(strlen($order->user->name)>0)
        $tbl .= '
        <strong>Тел.</strong> '.$order->phone.' '.$order->user->name.'<br/>';
        else
        $tbl .= '
        <strong>Тел.</strong> '.$order->phone.' '.$order->name.'<br/>';

        $tbl .= '
        <strong>Дата и время:</strong> '.$order->delivery_from.' - '.$order->delivery_till.'<br/>
       ';
       //echo $tbl;
       $pdf->writeHTML($tbl, true, false, false, false, '');
        
        
        
        //$pdf->Cell(0,10,"Example 002",1,1,'C');
       $pdf->Output("example_002.pdf", "I");
        /*
        if ($checked = $_POST['checkedOrder']) {
                    $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8');
                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor("Belyakov Yuriy");
                    $pdf->SetTitle("Orders");
                    $pdf->SetSubject("Orders");
                    $pdf->SetKeywords("Orders");
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->AddPage();
                    $pdf->SetFont('freeserif', '', 14);
                    $tbl = "" . date('d.m.Y', time()) . "<br>";
                    $pdf->SetFont('freeserif', '', 10);
                    $tbl .= '';
         
                    $printOrders = null;
                    foreach ($checked as $k => $v) {
                        $order = Order::model()->findByPk((int) $v);
                        $priority = Order::model()->getPriority($order->priority);
                        $tbl .= "";
                    }
                    $tbl .= "<table style="padding: 5px;" border="1"><tbody><tr bgcolor="#ccc"><th width="30%"><strong>РљР»РёРµРЅС‚</strong></th><th width="70%"><strong>РЎРѕРґРµСЂР¶Р°РЅРёРµ</strong></th></tr><tr><td><strong>РђРґСЂРµСЃ:</strong> {$order->client->title} - {$order->client->division} ({$order->client->address})<br><strong>РџСЂРёРѕСЂРёС‚РµС‚:</strong> {$priority}</td><td>{$order->text}</td></tr></tbody></table>";
         
                    $pdf->writeHTML($tbl, true, false, false, false, '');
         
                    $pdf->Output('orders.pdf', 'I');
                } else {
                    Yii::app()->user->setFlash('myOrder', 'Р’С‹Р±РµСЂРёС‚Рµ Р·Р°СЏРІРєРё РґР»СЏ РїРµС‡Р°С‚Рё.');
                    ;
                    $this->redirect(array('/order/myorder'));
                }*/
        
    }
}