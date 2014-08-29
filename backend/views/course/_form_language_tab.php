<?php
/** @var $this CourseController */
/** @var $model Course */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->fileUploadRow($model, "[{$language}]image_id", '_id'); ?>
<?php echo $form->textFieldRow($model, "[{$language}]calories", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]dishtype", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]dishid", array('class' => 'span9')); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>