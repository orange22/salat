<?php
Yii::import('common.extensions.shopping-cart.*');
class Cart extends EShoppingCart
{
    /**
     * Shop item model
     *
     * @var Product
     */
    public $model = null;

    /**
     * Get cart component
     *
     * @param string|array $params Cart ID or component config array
     * @return Cart
     */
    public static function getCart($params)
    {
        $cfg = array(
            'class' => 'Cart',
            'cartId' => 'cart'
        );
        if(is_string($params))
            $cfg['cartId'] = $params;
        if(is_array($params))
            $cfg = CMap::mergeArray($cfg, $params);

        /** @var $cart Cart */
        $cart = Yii::createComponent($cfg);
        $cart->init();

        return $cart;
    }

    public function getCost($withDiscount = true)
    {
        return number_format(parent::getCost($withDiscount), 2, '.', '');
    }

    /**
     * Get list of product
     * Product with quantity > 1 are duplicated in list
     *
     * @return array
     */
    public function getExpandedPositions()
    {
        $o = array();
        foreach($this->toArray() as $item)
        {
            /** @var $item Product|ECartPositionBehaviour */
            if($item->getQuantity() > 1)
            {
                $o = array_pad($o, count($o) + $item->getQuantity(), $item);
                continue;
            }

            $o[] = $item;
        }

        return $o;
    }

    /**
     * Restore cart info from cookies
     */
    public function restoreFromSession()
    {
    	$buff = isset(request()->cookies[$this->cartId]) ? request()->cookies[$this->cartId] : '';
        if(preg_match('/[^0-9,:]/i', $buff))
            return;
		$buff = array_filter(explode(',', $buff));
        if(!$buff)
            return;

        $productData = array();
        foreach($buff as $item)
        {
            list($id, $qty) = explode(':', $item);
            if(!$qty)
                continue;
            $productData[$id]= $qty;
        }
		$models = $this->model->fetchCartItems($productData);
        
        foreach($models as $model)
        {
            /** @var $model Product */
            $this->put($model, $productData[$model->id]);
        }
    }

    /**
     * Save cart state in cookies
     */
    protected function saveState()
    {
        $data = array();
        foreach($this as $item)
        {
            //CVarDumper::dump($item,3,true);
			 /** @var $item Product|ECartPositionBehaviour */
            $data[] = $item->getId().':'.$item->getQuantity();
        }
        $cookie = new CHttpCookie($this->cartId, implode(',', $data), array(
            'httpOnly' => true,
            'expire' => time() + 2592000,
        ));
        request()->cookies[$this->cartId] = $cookie;
    }
}