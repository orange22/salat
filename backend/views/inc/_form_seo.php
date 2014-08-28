<?php
/** @var $this LangController */
/** @var $form ActiveForm */
/** @var $model BaseActiveRecord */
/** @var $models BaseActiveRecord[] */
/** @var $languages array */
?>
<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'id' => 'seo-form',
    'model' => $model,
    'fieldsetLegend' => Yii::t('backend', 'Meta tags'),
    'enableAjaxValidation' => false,
)); ?>

    <?
   
    $this->widget('backend.components.SeoFormWidget', array(
       'form' => $form,
       'model' => $model,
   ));
    ?>
    <?php echo $form->hiddenField($model, 'id'); ?>

<?php $this->endWidget(); ?>
