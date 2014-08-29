<?php
/**
 * SEO form widget
 * Multi-language version
 *
 * @using Language
 * @using ActiveForm
 * @using LangActiveRecord
 */
class SeoFormWidget extends CWidget
{
    /**
     * Active Form
     *
     * @var ActiveForm
     */
    public $form = null;


    /**
     * Model
     *
     * @var LangActiveRecord
     */
    public $model = null;

    public function init()
    {
        parent::init();

        if(!$this->model)
            throw new CException(Yii::t('backend', 'Property SeoWidget.model cannot be empty.'));

        if(!$this->form)
            throw new CException(Yii::t('backend', 'Property SeoWidget.form cannot be empty.'));

      
    }

    public function run()
    {
        $this->renderFields();
    }

    protected function renderFields()
    {
        $seoModel = Seo::model()->findOrNew($this->model);

        echo $this->form->textAreaRow($seoModel, "title", array('rows' => 1, 'cols' => 50, 'class' => 'span9'));
        echo $this->form->textAreaRow($seoModel, "keywords", array('rows' => 3, 'cols' => 50, 'class' => 'span9'));
        echo $this->form->textAreaRow($seoModel, "description", array('rows' => 5, 'cols' => 50, 'class' => 'span9'));
        echo $this->form->hiddenField($seoModel, 'entity', array('value' => $this->model->classId(true)));
    }
}