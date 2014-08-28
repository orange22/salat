<?php
/** @var $this FileController */
/** @var $model File */
/** @var $form ActiveForm */
?>

<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'fieldsetLegend' => $legend,
)); ?>

<?php echo $form->textFieldRow($model, 'width'); ?>
<?php echo $form->textFieldRow($model, 'height'); ?>
<?php echo $form->textFieldRow($model, 'size'); ?>

<?php $this->endWidget(); ?>