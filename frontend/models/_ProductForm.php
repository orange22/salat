<?php
class ProductForm extends CFormModel
{
    public $id;
    public $info;

    /**
     * Selected Info ID
     *
     * @var int
     */
    protected $infoId;

    /**
     * Product model
     *
     * @var Product
     */
    protected $productModel = false;

    /**
     * Product info model
     *
     * @var ProductInfo
     */
    protected $infoModel = false;

    /**
     * Get product with info injected
     *
     * @return Product
     */
    public function getCompleteProduct()
    {
    	CVarDumper::dump($this->infoModel,10,true);
        //$this->productModel->setExactInfo($this->infoModel);

        return $this->productModel;
    }

    public function rules()
    {
        return array(
            array('id', 'checkProduct'),
            array('info', 'checkInfo'),
            array('info', 'minQuantity'),
        );
    }

    public function checkProduct()
    {
        if(!$this->productModel)
            $this->addError('id', Yii::t('theme', 'Product not found.'));
    }

    public function checkInfo()
    {
        if(!$this->infoModel)
            $this->addError('info', Yii::t('theme', 'Product size not found.'));
    }

    /**
     * Check product info minimal quantity allowed
     */
    public function minQuantity()
    {
        if($this->infoModel && $this->infoModel->quantity < 1)
            $this->addError('info', Yii::t('theme', 'Product is not available anymore.'));
    }

    /**
     * Fetch product model
     *
     * @return ProductForm
     */
    protected function fetchProduct()
    {
        if($this->productModel === false && $this->id)
        {
            $this->productModel = Product::model()->language()->active()->pid($this->id)->find();
        }

        return $this;
    }

    /**
     * Fetch selected info model
     *
     * @return ProductForm
     */
    protected function fetchInfo()
    {
        if($this->infoModel === false && $this->infoId && $this->productModel)
        {
            $this->infoModel = ProductInfo::model()->findByAttributes(array(
                'id' => $this->infoId,
                'product_pid' => $this->productModel->pid,
            ));
        }

        return $this;
    }

    protected function beforeValidate()
    {
        if($this->info)
            $this->infoId = array_shift(explode(':', $this->info));
        $this->fetchProduct()->fetchInfo();

        return parent::beforeValidate();
    }
}