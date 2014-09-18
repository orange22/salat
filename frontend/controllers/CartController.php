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
        $this->pageTitle="Салатник - Кошик";
        $orders=$this->cart->getPositions();
        if(!$orders)
            $this->redirect('/');
        $this->render('index',array('orders'=>$this->cart->getPositions(),'total'=>$this->cart->getCost()));
	}

    public function actionOrder()
    {
        $model=new OrderForm();
        $model->attributes = $_POST;
        $model->orders = $this->cart->getPositions();
        if($model->validate()){

            //$total=$this->cart->getCost()
            if($model->save($this->cart))
                $this->cart->clear();
            $this->redirect('/cart/success');
        }
        else
            $this->redirect('/cart/');
    }

    public function actionSuccess()
    {
        $this->render('/site/text',array('message'=>'Ваш заказ успешно принят!'));
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
    public function actionUpdate()
    {
        $productForm = new ProductForm();
        $productForm->attributes = $_GET;
        if(!$productForm->validate())
        {
            $errors = current($productForm->getErrors());
        }

        $positions=$this->cart->getPositions();

        $q=$positions[$_GET['id']]->CartPosition->quantity;
        $position=$productForm->fetchproduct();
            $this->cart->update($position,$q-1);
        $this->redirect('/cart/');
    }
    public function actionDelete($id)
    {
        $cart = $this->getCart();
        $cart->remove($id);
        $this->redirect('/cart/');
    }
}