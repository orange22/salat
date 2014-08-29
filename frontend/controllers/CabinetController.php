<?php

class CabinetController extends FrontController
{
	public function actionIndex($page=1)
	{
		
		$total=count(Dish::model()
		->with(
		array(
			'courses'=>array('with'=>array('coursetype'=>array('with'=>'coursetypeimage'))),'orderDishes'=>array('select'=>false, 'joinType'=>'INNER JOIN','with'=>array('order'=>array('joinType'=>'INNER JOIN','condition'=>'order.user_id='.Yii::app()->user->getId().'',)))
		))->active()->findAll());
			
		$Paging = new Paging('cabinet/page',self::PAGE_SIZE, $total, $page);
		$ordereddishes=Dish::model()
		->with(
		array(
			'dishImages',
			'dishtype'=> array('with'=>'dishtypeimage'), 
			'orderDishes'=>array('joinType'=>'INNER JOIN','with'=>array('order'=>array('joinType'=>'INNER JOIN','condition'=>'order.user_id='.Yii::app()->user->getId().' /*AND order.orderstate_id>2*/',)))
		))->sort('order.date_create DESC')->findAll();
		/*->limit(self::PAGE_SIZE,$Paging->getStart())*/
		$this->render('index',array(
		    'ordereddishes'=>$ordereddishes,
		    'pages'=>$Paging->GetHTML(),
		));	
	}
	
	
	public function actionSettings() {
		
		$this->render('settings', array(
	    'userdata'=>Yii::app()->user->getData(),
	    ));
	}
	

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}