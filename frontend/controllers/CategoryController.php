<?php

class CategoryController extends FrontController
{
    public function actionIndex($category)
	{
        $products=Prod::model()->cache()->with('category')->active()->sort()->findAll('category.code="'.$category.'"');
        $this->pageTitle="Салатник - ".$products[0]->category->title;
        $this->render('index',array('products'=>$products));
	}
}