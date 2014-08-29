<?php
/** @var $this DiscountController */
/** @var $model Discount */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]discount", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]disccode", array('class' => 'span9', 'maxlength' => 55)); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]user_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]discounttype_id", array()); ?>
<?php echo $form->textFieldRow($model, "[{$language}]activations", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, 'date_end', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_end"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>