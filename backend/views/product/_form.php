<?php
/** @var $this ProductController */
/** @var $model Product */
/** @var $models Product[] */
/** @var $form ActiveForm */
?>

<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'model' => $model,
    'fieldsetLegend' => $legend,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:formAfterValidate',
    ),
)); ?>

    <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
    <?php echo $form->dropDownListRow($model, 'category_id', array()); ?>
    <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
    <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'price', array('class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'weight', array('class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_create'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
