<?php

class ActionController extends FrontController
{
	public function actionIndex($page=1)
	{
		$total=count(Action::model()->active()->findAll());
			
		$Paging = new Paging('action/page',self::PAGE_SIZE, $total, $page);
		$topdishes=Action::model()->active()->sort()->limit(self::PAGE_SIZE,$Paging->getStart())->findAll();
		
		
		$this->render('index',array(
		    'topdishes'=>$topdishes,
		    'pages'=>$Paging->GetHTML(),
		));
	}
	

}