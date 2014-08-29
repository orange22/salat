<?php

class CartController extends FrontController
{
    public function actionIndex($category)
	{
        $this->render('index',array());
	}
}