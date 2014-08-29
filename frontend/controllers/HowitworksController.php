<?php

class HowitworksController extends FrontController
{
	public function actionIndex()
	{
		$topdishes=About::model()->sort()->findAll();
		$this->render('index',array(
		    'topdishes'=>$topdishes
		));
	}
}