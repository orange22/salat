<?php
/**
 * ActiveForm
 *
 * @uses TbActiveForm
 * @uses UploadedWidget
 * @uses Option
 */
class ActiveForm extends TbActiveForm
{
    /**
     * Fieldset legend text
     *
     * @var string
     */
    public $fieldsetLegend = '';

    /**
     * Form submit actions
     *
     * @var array
     */
    public $formActions = array('save', 'apply', 'cancel');

    /**
     * Model
     *
     * @var CModel
     */
    public $model = null;

    /**
     * Override form type
     *
     * @var string
     */
    public $overrideType = null;

    public function init()
    {
        $this->id = $this->getController()->getId().'-form';
        $this->focus = 'input[type!=hidden]:first';
        $this->type = 'horizontal';
        if($this->overrideType)
            $this->type = $this->overrideType;

        parent::init();
        if($this->fieldsetLegend)
            printf("<fieldset>\n<legend>%s</legend>", $this->fieldsetLegend);
        else
            echo '<fieldset>';
    }

    public function run()
    {
        $this->renderActions();
        echo '</fieldset>';
        parent::run();
    }

    /**
     * Image/file upload input
     *
     * @param CActiveRecord $model the data model
     * @param string $attribute the attribute
     * @param string $related model related field
     * @param array $htmlOptions additional HTML attributes
     * @param string $language language code
     * @return string
     */
    public function fileUploadRow($model, $attribute, $related = null, $htmlOptions = array(), $language = null)
    {
        $cleanAttr = Tool::resolveAttribute($attribute);
        $hint = $this->getImageHint($model, $cleanAttr);
        if($hint)
        {
            if(!isset($htmlOptions['hint']))
                $htmlOptions['hint'] = '';
            $htmlOptions['hint'] .= $hint;
        }

        ob_start();
        echo $this->fileFieldRow($model, $attribute, $htmlOptions);
        echo $this->hiddenField($model, $attribute, array(
            'id' => CHtml::getIdByName(CHtml::resolveName($model, $tmp = $attribute)).'_current',
            'name' => (isset($htmlOptions['name']) ? $htmlOptions['name'] : CHtml::activeName($model, $attribute)),
        ));

        unset($htmlOptions['name'], $htmlOptions['hint']);
        $this->widget('backend.components.UploadedWidget', array(
            'model' => $model,
            'related' => $related,
            'language' => $language,
            'attribute' => $cleanAttr,
            'htmlOptions' => $htmlOptions,
        ));

        return ob_get_clean();
    }

    /**
     * Wrapper for image
     *
     * @param CActiveRecord $model the data model
     * @param string $attribute the attribute
     * @param string $related model related field
     * @param array $htmlOptions additional HTML attributes
     */
    public function imageUploadRow($model, $attribute, $related = null, $htmlOptions = array())
    {
        $this->fileUploadRow($model, $attribute, $related, $htmlOptions);
    }

    /**
     * Render form action buttons
     */
    protected function renderActions()
    {
        if(empty($this->formActions))
        {
            return;
        }

        echo '<div class="form-actions">';
        foreach($this->formActions as $action)
        {
            switch($action)
            {
                case 'save':
                    echo CHtml::submitButton(
                        $this->model && isset($this->model->isNewRecord) && $this->model->isNewRecord
                            ? Yii::t('backend', 'Create')
                            : Yii::t('backend', 'Save'),
                        array(
                            'class' => 'btn btn-primary',
                            'name' => 'save',
                        ));
                    break;
                case 'apply':
                    echo CHtml::submitButton(Yii::t('backend', 'Apply'), array(
                        'class' => 'btn btn-primary',
                        'name' => 'apply',
                    ));
                    break;
                case 'cancel':
                    echo CHtml::link(Yii::t('backend', 'Cancel'), array('admin'), array(
                        'class' => 'btn',
                        'name' => 'cancel',
                    ));
                    break;
                default:
                    echo $action;
            }
            echo "\n";
        }
        echo '</div>';
    }

    /**
     * Validates an array of model instances and returns the results in JSON format.
     * This is a helper method that simplifies the way of writing AJAX validation code for tabular input.
     *
     * @param mixed $models an array of model instances.
     * @param array $attributes list of attributes that should be validated. Defaults to null,
     * meaning any attribute listed in the applicable validation rules of the models should be
     * validated. If this parameter is given as a list of attributes, only
     * the listed attributes will be validated.
     * @param boolean $loadInput whether to load the data from $_POST array in this method.
     * If this is true, the model will be populated from <code>$_POST[ModelClass][$i]</code>.
     * @return string the JSON representation of the validation error messages.
     */
    public static function validateTabular($models, $attributes = null, $loadInput = true)
    {
        $result = array();
        if(!is_array($models))
        {
            $models = array($models);
        }

        foreach($models as $i => $model)
        {
            /** @var $model LangActiveRecord */
            if($loadInput && isset($_POST[get_class($model)]))
            {
                $model->attributes = $_POST[get_class($model)];
            }
            if($loadInput && isset($_POST[get_class($model)][$i]))
            {
                $model->attributes = $_POST[get_class($model)][$i];
            }

            $model->validate($attributes);
            $fixedAttr = $model->fixedAttributes();
            foreach($model->getErrors() as $attribute => $errors)
            {
                if(in_array($attribute, $fixedAttr))
                {
                    $result[CHtml::activeId($model, $attribute)] = $errors;
                }
                else
                {
                    $result[CHtml::activeId($model, '['.$i.']'.$attribute)] = $errors;
                }
            }
        }

        return function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);
    }

    /**
     * Get image dimensions hint
     *
     * @param CActiveRecord $model
     * @param string $attribute
     * @return string
     */
    protected function getImageHint($model, $attribute)
    {
        $o = array();

        if(isset($model->gallery))
        {
            $hintData = (array)Option::getOpt(
                'image.'.strtolower(get_class($model)).'.'.$model->gallery->type.".{$attribute}"
            );
        }
        else
        {
            $hintData = (array)Option::getOpt('image.'.strtolower(get_class($model)).'.'.$attribute);
        }

        if(!isset($hintData['size']))
        {
            return $o;
        }

        list($w, $h) = explode(',', $hintData['size']);
        if($w)
        {
            $o[] = Yii::t('backend', 'width: {width}px', array(
                '{width}' => $w,
            ));
        }
        if($h)
        {
            $o[] = Yii::t('backend', 'height: {height}px', array(
                '{height}' => $h,
            ));
        }

        return implode(', ', $o);
    }
}