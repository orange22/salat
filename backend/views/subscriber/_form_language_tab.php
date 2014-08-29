<?php
/** @var $this SubscriberController */
/** @var $model Subscriber */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]email", array('class' => 'span9', 'maxlength' => 55)); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>