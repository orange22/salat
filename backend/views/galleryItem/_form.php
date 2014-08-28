<?php
/** @var $this GalleryItemController */
/** @var $model GalleryItem */
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
    'formActions' => array('save', 'apply', 'cancel' =>
        CHtml::link(Yii::t('backend', 'Cancel'), $this->postBackNav($model->post_pid), array(
            'class' => 'btn',
            'name' => 'cancel',
        ))
    )
)); ?>

    <?php echo $form->hiddenField($model, 'post_pid'); ?>
    <?php echo $form->fileUploadRow($model, 'mini_id', 'mini'); ?>
    <?php echo $form->fileUploadRow($model, 'middle_id', 'middle'); ?>
    <?php echo $form->fileUploadRow($model, 'big_id', 'big'); ?>
    <?php echo $form->textAreaRow($model, 'data', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>
    <?php echo CHtml::hiddenField('returnUrl', request()->getParam('returnUrl')); ?>

<?php $this->endWidget(); ?>
