<?php
/** @var $this CommentsController */
/** @var $model Comments */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]dish_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]user_id", array()); ?>
<?php echo $form->textAreaRow($model, "[{$language}]comment", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>