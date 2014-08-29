<?php
/** @var $this VideoController */
/** @var $model Video */
/** @var $models Video[] */
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
    <?php echo $form->textFieldRow($model, 'url', array('class' => 'span9', 'maxlength' => 255)); ?>
    <?php echo $form->dropDownListRow($model, 'videotype_id', Videotype::model()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'course_id', Course::model()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>
    <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>

<?php $this->endWidget(); ?>
