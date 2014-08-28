<?php
/** @var $this DishController */
/** @var $model Dish */
/** @var $models Dish[] */
/** @var $form ActiveForm */
?>

<?php /*
$form = $this->beginWidget('backend.components.ActiveForm', array(
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
));*/
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
        'afterValidate' => 'js:function($form, data, hasError) {
            return true;
        }',
    ),
)); ?>

<div class="row">
    <div class="span6">
        <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
        <?php echo $form->dropDownListRow($model, 'dishtype_id', CHtml::listData(Dishtype::model()->findAll('t.dpid=18'),'id','title')); ?>
        <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
        <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
        <?php $this->tinymce(CHtml::resolveName($model, $tmp = "detail_text")); ?>
        <?php echo $form->textFieldRow($model, 'price', array('class' => 'span2')); ?>
        <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
        <?php echo $form->checkBoxRow($model, 'status'); ?>
    </div>

</div>
<?php $this->endWidget(); ?>