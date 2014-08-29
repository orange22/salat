<?php
/** @var $this DishController */
/** @var $model Dish */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_create"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->textAreaRow($model, "[{$language}]detail_text", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]prepare", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]steps", array('class' => 'span9')); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>