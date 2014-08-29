<?php
/** @var $this StepController */
/** @var $model Step */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textAreaRow($model, "[{$language}]preview_text", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->textAreaRow($model, "[{$language}]detail_text", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]step", array('class' => 'span9')); ?>
<?php echo $form->textAreaRow($model, "[{$language}]advice", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]user_id", array()); ?>
<?php echo $form->fileUploadRow($model, "[{$language}]image_id", '_id'); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]dish_id", array()); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>