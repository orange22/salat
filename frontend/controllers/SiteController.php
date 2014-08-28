<?php

class SiteController extends FrontController
{
    public function actionIndex()
	{
        $this->render('index',array());
	}
}