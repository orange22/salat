<?php

class PressController extends FrontController
{
	public $test;
	public function actionIndex()
	{
		$items=Press::model()->with()->sort()->active()->findAll();
		$this->render('index',array('items'=>$items));
	}
	
	
	public function actionView($id=null) {
		//$managers=User::model()->with(array('userUsertypes'=>array('joinType'=>'inner join')))->findAll('userUsertypes.id=3');
		$page=Press::model()->active()->findByPk($id);
		
		if($seo=Seo::model()->find('t.pid=:id AND t.entity="'.$this->id.'"', array(':id'=>$page->id)))
		{
			$this->seo_title=$seo->title;
			$this->seo_description=$seo->description;
			$this->seo_keywords=$seo->keywords;
		}
		
		$this->render('view', array('page'=>$page));
	}
}