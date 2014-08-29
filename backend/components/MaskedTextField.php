<?php
/**
 * MaskedTextField
 * Used Bootstrap input
 */
class MaskedTextField extends CMaskedTextField
{
    /**
     * TwitterBootstrap Active Form
     *
     * @var TbActiveForm
     */
    public $form = null;

    public function run()
    {
        if($this->mask == '')
            throw new CException(Yii::t('yii', 'Property MaskedTextField.mask cannot be empty.'));

        if(!$this->form)
            throw new CException(Yii::t('yii', 'Property MaskedTextField.form cannot be empty.'));

        if(!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = CHtml::getIdByName(CHtml::resolveName($this->model, $this->attribute));

        $this->registerClientScript();
        echo $this->form->textFieldRow($this->model, $this->attribute, $this->htmlOptions);
    }
}