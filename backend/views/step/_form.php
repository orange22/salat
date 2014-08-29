<?php
/** @var $this StepController */
/** @var $model Step */
/** @var $models Step[] */
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
    <?php echo $form->textFieldRow($model, 'step', array('class' => 'span9')); ?>
    <?php echo $form->dropDownListRow($model, 'course_id', Course::model()->listData()); ?>
    <?php echo $form->textAreaRow($model, 'preview_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php $this->tinymce(CHtml::resolveName($model, $tmp = "detail_text")); ?>
    <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->listData(),array('empty'=>'')); ?>
    <?php echo $form->textAreaRow($model, 'advice', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
   

<?php $this->endWidget(); ?>
