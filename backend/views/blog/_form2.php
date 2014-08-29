<?php
/** @var $this BlogController */
/** @var $model Blog */
/** @var $models Blog[] */
/** @var $form ActiveForm */
?>

<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'model' => $model,
    'fieldsetLegend' => $legend,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:formAfterValidate',
    ),
)); ?>
<div class="row">
    <div class="span6">
    <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->sort('email')->listData()); ?>
    <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
    <?php echo $form->textAreaRow($model, 'preview_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php $this->tinymce(CHtml::resolveName($model, $tmp = "detail_text")); ?>
    <?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_create'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
    <?php echo $form->textFieldRow($model, 'views', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'likes', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'comments', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>]
    <?php echo $form->checkBoxRow($model, 'status'); ?>
    </div>
    <div class="span6">

    </div>
</div>
<?php $this->endWidget(); ?>

