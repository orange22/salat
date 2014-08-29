<?php
/** @var $this ProductController */
/** @var $model Product */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]producttype_id", array()); ?>
<?php echo $form->fileUploadRow($model, "[{$language}]image_id", '_id'); ?>
<?php echo $form->textAreaRow($model, "[{$language}]detail_text", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]price", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]weight", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_create"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>