<?php
class OrdertestController extends BackController
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
		
	protected function afterActionDone($model)
    {
		$user=User::model()->findByPk($model->user_id);	
		if(isset($_POST['Order']['orderstate_id'])){
			if($_POST['Order']['orderstate_id']>2 && $model->mail_open<1){
					$message = new YiiMailMessage;
					$message_body='
					Видеорецепт Личного Повара<br><br>
					Мы хотим, чтобы у вас точно все получилось, поэтому видеорецепт приготовления заказаного вами набора уже доступен в вашем кабинете, на сайте.
					Вы можете перейти по ссылке: http://lpovar.com.ua/cabinet/<br><br>
					С уважением,<br>
					Личный Повар';
					$message->setBody($message_body, 'text/html');
					$message->subject = 'Видеорецепт Личного Повара';
					$message->addTo($user->email);
					$message->from = array(Yii::app()->params['adminEmail']=>'Личный Повар');
					Yii::app()->mail->send($message);
					$model->mail_open=1;
					$model->save();
			}
		}	
			
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
		$discount=100;
        if($user->discount>0){
            $discount=$user->discount;
        }elseif($user->discount<1 && $_POST['Order']['discount_id']>0){
            $disc=Discount::model()->findByPk($_POST['Order']['discount_id']);
            if($disc->discount>0)
            $discount=$disc->discount;
        }
        
        if($discount>0)
        $discount=1-($discount/100);
	
        if(!$discount)
        $discount=1;
		$sql='
			SELECT SUM(total) FROM ((SELECT 
			SUM(gs_order_dish.`quantity`*gs_dish.price)*'.$discount.' AS `total`
			FROM
			  gs_order_dish
			INNER JOIN gs_dish
			ON gs_dish.id=gs_order_dish.`dish_id`
			WHERE gs_order_dish.`order_id`='.$model->id.')
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
}