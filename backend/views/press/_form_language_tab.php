<?php
/** @var $this PressController */
/** @var $model Press */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->fileUploadRow($model, "[{$language}]image_id", '_id'); ?>
<?php echo $form->textAreaRow($model, "[{$language}]preview_text", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->textAreaRow($model, "[{$language}]detail_text", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]link", array('class' => 'span9', 'maxlength' => 55)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>