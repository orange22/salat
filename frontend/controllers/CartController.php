<?php

class CartController extends FrontController
{
    public $totalCart;
    public $_identity;
    public function init()
    {
        parent::init();
        Yii::import('common.extensions.yii-mail.*');
    }

    public function actionIndex()
	{
        $orders=$this->cart->getPositions();
        if(!$orders)
            $this->redirect('/');
        $this->render('index',array('orders'=>$this->cart->getPositions(),'total'=>$this->cart->getCost()));
	}

    public function actionAdd()
    {
        $productForm = new ProductForm();
        $productForm->attributes = $_GET;
        if(!$productForm->validate())
        {
            $errors = current($productForm->getErrors());
        }
        if(isset($_REQUEST['q']))
            $this->cart->put($productForm->fetchproduct(),intval($_REQUEST['q']));
        else
            $this->cart->put($productForm->fetchproduct());
        $this->redirect('/cart/');
    }
    public function actionDelete($id)
    {
        $cart = $this->getCart();
        $cart->remove($id);
        $this->redirect('/cart/');
    }
}