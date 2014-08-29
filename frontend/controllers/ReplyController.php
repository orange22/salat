<?php

class ReplyController extends FrontController
{
    public function actionIndex()
    {
        $replies=Reply::model()->sort()->active()->findAll();
        $this->render('index',array('replies'=>$replies));
    }
  
}