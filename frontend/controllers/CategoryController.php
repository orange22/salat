<?php

class CategoryController extends FrontController
{
    public function actionIndex($category)
	{
        $this->render('index',array());
	}
}