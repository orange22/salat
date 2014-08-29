<?php
class OrderController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::import('ext.shopping-cart.*');
    }

    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
            'ajaxOnly + view, update, customer, checkout',
            'postOnly + update, customer, checkout'
        ));
    }

    public function actionView()
    {
        $cart = $this->getCart();

        $pid = array();
        foreach($cart->getPositions() as $item)
            $pid[] = $item->pid;

        $info = array();
        foreach(ProductInfo::model()->findAllByAttributes(array('product_pid' => $pid)) as $item)
            $info[$item['product_pid']][] = $item;

        $this->sendJsonResponse(array(
            'error' => false,
            'content' => $this->renderPartial('_view', array(
                'cart' => $cart,
                'info' => $info,
                'modules' => Module::model()->fetch()
            ), true),
        ));
    }

    public function actionUpdate()
    {
        $cart = $this->getCart();
        if(!isset($_POST['Product']))
        {
            $cart->clear();
        }
        else
        {
            $postData = $_POST['Product'];

            $products = array();
            foreach($postData as $data)
            {
                list($infoId,) = explode(':', $data['info']);
                $itemKey = $data['id'].':'.$infoId;
                if(!isset($products[$itemKey]))
                    $products[$itemKey] = 1;
                else
                    ++$products[$itemKey];
            }

            foreach($cart->getPositions() as $key => $item)
            {
                /** @var $item IECartPosition|ECartPositionBehaviour */
                if(!isset($products[$key]))
                {
                    $cart->remove($key);
                    continue;
                }

                if($item->getQuantity() != $products[$key])
                {
                    $cart->update($item, $products[$key]);
                    unset($products[$key]);
                    continue;
                }
                unset($products[$key]);
            }

            Yii::import('app.models.forms.ProductForm');
            // for now we have only products with changed info, i.e. new products
            foreach($products as $pidInfo => $qty)
            {
                $productForm = new ProductForm();

                list($id, $infoId) = explode(':', $pidInfo);
                $productForm->setAttributes(array(
                    'id' => $id,
                    'info' => $infoId
                ));

                if($productForm->validate())
                    $cart->put($productForm->getCompleteProduct(), $qty);
            }
        }

        $this->sendJsonResponse(array(
            'error' => false,
            'content' => $this->renderPartial('/catalog/inc/_cart_items', array('cart' => $cart), true),
        ));
    }

    public function actionCustomer()
    {
        if(!isset($_POST['Order']) || !isset($_POST['Product']))
            app()->end();

        $cart = $this->getCart();
        $this->sendJsonResponse(array(
            'error' => false,
            'content' => $this->renderPartial('_customer', array(
                'cart' => $cart,
                'shipping' => isset($_POST['Order']['shipping']) ? $_POST['Order']['shipping'] : null,
                'payment' => isset($_POST['Order']['payment']) ? $_POST['Order']['payment'] : null,
                'package' => isset($_POST['Order']['package']) ? $_POST['Order']['package'] : null,
            ), true)
        ));
    }

    public function actionCheckout()
    {
        if(!isset($_POST['Order']))
            app()->end();

        Yii::import('app.models.forms.OrderForm');
        $orderForm = new OrderForm();

        //  client data
        $orderForm->attributes = $_POST['Order'];
        if(!$orderForm->validate())
        {
            $errors = current($orderForm->getErrors());
            $this->sendJsonResponse(array(
                'content' => $this->renderPartial('_message', array(
                    'error' => true,
                    'message' => $errors[0]
                ), true),
                'error' => true
            ));
        }

        // cart processing
        $cart = $this->getCart();
        $orderForm->setCart($cart);
        try
        {
            $orderForm->save();
        }
        catch(CException $e)
        {
            $this->sendJsonResponse(array(
                'content' => $this->renderPartial('_message', array(
                    'error' => true,
                    'message' => $e->getMessage()
                ), true),
                'error' => true
            ));
        }

//        $cart->clear();

        $paymentMethod = "performPostOrder{$orderForm->payment->name}";
        if(method_exists($this, $paymentMethod))
        {
            $this->$paymentMethod($orderForm);
        }
        else
        {
            $this->sendJsonResponse(array(
                'content' => $this->renderPartial('_message', array(), true)
            ));
        }
    }

    public function actionCallback($module)
    {
        switch($module)
        {
            case 'liqpay':
                $responseXml = base64_decode(request()->getParam('operation_xml'));

                $signature = Option::getOpt('order.payment.liqpay.signature');
                $checkSignature = base64_encode(sha1($signature.$responseXml.$signature, true));

                if($checkSignature !== request()->getParam('signature'))
                    throw new CHttpException(500, Yii::t('theme', 'Invalid payment gateway response.'));

                $xml = simplexml_load_string($responseXml);
                if((string)$xml->response->status !== 'success')
                    app()->end();

                $orderId = (int)$xml->response->order_id;

                /** @var $order Order */
                $order = Order::model()->findByPk($orderId);
                $order->confirm(Option::getOpt('order.payment.liqpay.status.confirm'));

                break;
        }
    }

    /**
     * Perform payment after order processing
     *
     * @param OrderForm $orderForm
     * @throws CHttpException
     */
    protected function performPostOrderLiqpay($orderForm)
    {
        $merchantId = Option::getOpt('order.payment.liqpay.merchant_id');
        $signature = Option::getOpt('order.payment.liqpay.signature');
        if(!$merchantId || !$signature)
            throw new CHttpException(500, Yii::t('theme', 'Liqpay merchant ID and/or signature not defined.'));

        $xml = '
            <request>
                <version>1.2</version>
                <merchant_id>{merchant_id}</merchant_id>
                <result_url>{result_url}</result_url>
                <server_url>{server_url}</server_url>
                <order_id>{order_id}</order_id>
                <amount>{amount}</amount>
                <currency>{currency}</currency>
                <description>{comment}</description>
                <default_phone>{default_phone}</default_phone>
                <pay_way>{pay_way}</pay_way>
                <goods_id>{goods_id}</goods_id>
            </request>
        ';

        $goods = array();
        foreach($orderForm->getOrder()->products as $item)
            $goods[] = $item->product_pid;

        $requestXml = trim(strtr($xml, array(
            '{merchant_id}' => $merchantId,
            '{result_url}' => $this->wrapToAbsolute($this->url('/', array('notify_order' => 1))),
            '{server_url}' => $this->createAbsoluteUrl('order/callback', array('module' => 'liqpay')),
            '{order_id}' => $orderForm->getOrder()->getPrimaryKey(),
            '{amount}' => $orderForm->getOrder()->total,
            '{currency}' => $orderForm->getOrder()->currency,
            '{comment}' => '',
            '{default_phone}' => '',
            '{pay_way}' => Option::getOpt('order.payment.liqpay.pay_way', 'liqpay'),
            '{goods_id}' => implode(',', $goods),
        )));

        $this->sendJsonResponse(array(
            'form' => $this->renderPartial('_liqpay', array(
                'action' => 'https://liqpay.com/?do=clickNbuy',
                'xml' => base64_encode($requestXml),
                'signature' => base64_encode(sha1($signature.$requestXml.$signature, 1)),
            ), true)
        ));
    }
}
