<?php

class PageController extends FrontController
{
	public $test;
	public function actionIndex()
	{
		
		/*
		$topdishes=Dish::model()->with(array('dishImages','dishtype'=>array('with'=>'dishtypeimage')))->findAll();
				$teasers=Teaser::model()->with('image')->findAll();
				//CVarDumper::dump($dishes,10,true);
				//foreach($topdishes as $d)
				//CVarDumper::dump($d->dishImages[0]->image->asHtmlImage(),10,true);
				$this->render('index',array('topdishes'=>$topdishes,'teasers'=>$teasers));*/
		
	}
	
	
	public function actionView($code=null) {
		
		//$managers=User::model()->with(array('userUsertypes'=>array('joinType'=>'inner join')))->findAll('userUsertypes.id=3');
		$page=Page::model()->active()->find('t.code=:code', array(':code'=>$code));
		
		if($seo=Seo::model()->find('t.pid=:id AND t.entity="page"', array(':id'=>$page->id)))
		{
			$this->seo_title=$seo->title;
			$this->seo_description=$seo->description;
			$this->seo_keywords=$seo->keywords;
		}
		
		$this->render('view', array('page'=>$page));
	}
}