<?php
/** @var $this VideoController */
/** @var $model Video */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]url", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]videotype_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]course_id", array()); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->fileUploadRow($model, "[{$language}]image_id", '_id'); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>