<?php

class SiteController extends FrontController
{
    public function actionIndex()
	{
        //echo CHtml::ajaxLink('delete', 'delete/'.$data->id, array('type'=>'POST', 'data'=>array('YII_CSRF_TOKEN' => Yii::app()->request->csrfToken)));
        $this->render('index',array());
	}
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