<?php

class SiteController extends FrontController
{
    public function init()
    {
       parent::init();
       Yii::import('common.extensions.yii-mail.*');
    }
	
	public function actionIndex()
	{
        $this->render('index');
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