<?php
/** @var $this OptionController */
/** @var $model Option */
/** @var $form ActiveForm */
?>

<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'model' => $model,
    'fieldsetLegend' => $legend,
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>

<?php if(Yii::app()->user->checkAccess('Option.Create')) { ?>
    <?php echo $form->textFieldRow($model, 'title', array('size' => 32, 'maxlength' => 64, 'class' => 'span5')); ?>
    <?php echo $form->textFieldRow($model, 'key', array('size' => 32, 'maxlength' => 64, 'class' => 'span5')); ?>

        <div class="control-group ">
            <label class="control-label" for="<?php echo CHtml::getIdByName("Option[group]"); ?>"><?php echo $model->getAttributeLabel('group'); ?></label>
            <div class="controls">
                <?php $this->widget('TbTypeahead', array(
                    'model' => $model,
                    'attribute' => 'group',
                    'options' => array(
                        'source' => Option::getGroups(),
                        'items' => 999,
                        'matcher' => "js:function(item) {return ~item.toLowerCase().indexOf(this.query.toLowerCase());}",
                    ),
                )); ?>
            </div>
        </div>

    <?php echo $form->dropDownListRow($model, 'role', User::getRoleList()); ?>
    <?php echo $form->dropDownListRow($model, 'type', Option::getTypes()); ?>
    <?php echo $form->textAreaRow($model, 'config', array('rows' => 5, 'cols' => 50, 'class' => 'span5', 'hint' => Yii::t('cp', 'INI style'))); ?>
    <?php echo $form->textAreaRow($model, 'hint', array('rows' => 2, 'cols' => 50, 'class' => 'span5')); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'serialized'); ?>
    <?php echo $form->checkBoxRow($model, 'i18n'); ?>
<?php } ?>

<?php
if(!$model->i18n)
{
    switch($model->type)
    {
        case 'textField':
            echo $form->textFieldRow($model, 'value', array('size' => 32, 'class' => 'span5'));
            break;
        case 'textArea':
            echo $form->textAreaRow($model, 'value', array('rows' => 10, 'cols' => 50, 'class' => 'span5'));
            break;
        case 'fileUpload':
            echo $form->fileUploadRow($model, 'value', 'value', array('width' => 100));
            break;
        default:
    }
}
?>
<hr/>

<?php $this->endWidget(); ?>