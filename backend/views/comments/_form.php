<?php
/** @var $this CommentsController */
/** @var $model Comments */
/** @var $models Comments[] */
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
    <?php echo $form->dropDownListRow($model, 'dish_id', Dish::model()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->listData()); ?>
    <?php echo $form->textAreaRow($model, 'comment', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
