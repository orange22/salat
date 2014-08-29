<?php
/** @var $this DishImageController */
/** @var $model DishImage */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->dropDownListRow($model, "[{$language}]dish_id", array()); ?>
<?php echo $form->fileUploadRow($model, "[{$language}]image_id", '_id'); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->fileUploadRow($model, "[{$language}]thumb_id", '_id'); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>