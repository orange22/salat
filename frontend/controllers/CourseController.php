<?php

class CourseController extends FrontController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionView($id)
	{
		$course=Course::model()->with(array('dish','recipe'))->find('t.id=:id', array(':id'=>$id));
		
		//if(yii::app()->user->getId()>0)	
		//$order=OrderDish::model()->with(array('order'=>array('joinType'=>'INNER JOIN', 'on'=>'order.orderstate_id>2 AND order.user_id='.yii::app()->user->getId().'')))->find('t.dish_id=:id', array(':id'=>$course->dish_id));
		$order=OrderDish::model()->find('t.dish_id=:id', array(':id'=>$course->dish_id));
		
		//if(!isset($order))
		//$this->redirect('/');
		
		$steps=Step::model()->with(array('image','user'))->sort()->active()->findAll('t.course_id=:id', array(':id'=>$id));
		$videos=Video::model()->sort()->active()->findAll('t.course_id='.$id);
		$this->render('view', array('course'=>$course, 'steps'=>$steps, 'videos'=>$videos));
	}

}