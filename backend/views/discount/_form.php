<?php
/** @var $this DiscountController */
/** @var $model Discount */
/** @var $models Discount[] */
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
    <?php echo $form->textFieldRow($model, 'discount', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'disccode', array('class' => 'span2', 'maxlength' => 55)); ?>
    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->sort('email')->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'discounttype_id', Discounttype::model()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'discountmode_id', Discountmode::model()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'activations', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'date_end', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_end'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
