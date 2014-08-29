<?php
class DishForm extends CFormModel
{
    public $id;

     /**
     * dish model
     *
     * @var dish
     */
    protected $dishModel = false;

    /**
     * dish info model
     *
     * @var dishInfo
     */
     public function rules()
    {
        return array(
       		array('id', 'required'),
            array('id', 'checkdish')
        );
    }

    public function checkdish()
    {
        $this->dishModel=Dish::model()->findByPk($this->id);
        if(!$this->dishModel){
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
    public function fetchdish()
    {
        return Dish::model()->findByPk($this->id);
    }

    /*protected function beforeValidate()
    {
        
        if($this->info)
                    $this->infoId = array_shift(explode(':', $this->info));
                $this->fetchdish()->fetchInfo();
        
                return parent::beforeValidate();
        
    }*/
}