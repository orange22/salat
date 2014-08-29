<?php
/** @var $this UsertypeController */
/** @var $model Usertype */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]name", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>