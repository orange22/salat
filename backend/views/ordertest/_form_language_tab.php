<?php
/** @var $this OrderController */
/** @var $model Order */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->dropDownListRow($model, "[{$language}]user_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]paytype_id", array()); ?>
<?php echo $form->textFieldRow($model, "[{$language}]name", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]phone", array('class' => 'span9', 'maxlength' => 55)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]delivery_from", array('class' => 'span9', 'maxlength' => 55)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]delivery_to", array('class' => 'span9', 'maxlength' => 55)); ?>
<?php echo $form->textAreaRow($model, "[{$language}]delivery_address", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_create"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]discount_id", array()); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>