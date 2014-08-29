<?php
/** @var $this DishImageController */
/** @var $model DishImage */
/** @var $models DishImage[] */
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

    <?php echo $form->dropDownListRow($model, 'dish_id', Dish::model()->listData()); ?>
    <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>

<?php $this->endWidget(); ?>
