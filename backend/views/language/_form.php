<?php
/** @var $this LanguageController */
/** @var $model Language */
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

    <?php echo $form->textFieldRow($model, 'id', array('class' => 'span2', 'maxlength' => 2)); ?>
    <?php echo $form->textFieldRow($model, 'locale', array('class' => 'span2', 'maxlength' => 16)); ?>
    <?php echo $form->textFieldRow($model, 'title', array('class' => 'span4', 'maxlength' => 256)); ?>
    <?php echo $form->textFieldRow($model, 'title_alt', array('class' => 'span4', 'maxlength' => 256)); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'public'); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
