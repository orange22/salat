<?php
class ProductForm extends CFormModel
{
    public $id;

    protected $productModel = false;

     public function rules()
    {
        return array(
       		array('id', 'required'),
            array('id', 'checkproduct')
        );
    }

    public function checkproduct()
    {
        $this->productModel=Prod::model()->findByPk($this->id);
        if(!$this->productModel){
		    $this->addError('id', Yii::t('theme', 'dish not found.'));
		}
    }

    

    /**
     * Check dish info minimal quantity allowed
     */
    public function minQuantity()
    {
        if($this->infoModel && $this->infoModel->quantity < 1)
            $this->addError('info', Yii::t('theme', 'dish is not available anymore.'));
    }

    /**
     * Fetch dish model
     *
     * @return dishForm
     */
    public function fetchproduct()
    {
        return Prod::model()->findByPk($this->id);
    }

    /*protected function beforeValidate()
    {
        
        if($this->info)
                    $this->infoId = array_shift(explode(':', $this->info));
                $this->fetchdish()->fetchInfo();
        
                return parent::beforeValidate();
        
    }*/
}