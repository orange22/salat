<?php
/** @var $this PageController */
/** @var $form ActiveForm */
/** @var $model Page */
/** @var $models Page[] */
/** @var $languages array */
?>
<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'model' => $model,
    'fieldsetLegend' => Yii::t('backend', 'Meta tags'),
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:formAfterValidate',
    ),
)); ?>

    <?php $this->renderPartial('//inc/_form_tabs', array(
        'models' => $models,
        'languages' => $languages,
        'forceUnique' => true,
        'tabFileName' => '//inc/_form_seo_tab',
        'tplVars' => array(
            'form' => $form,
        ),
    )); ?>
    <?php echo $form->hiddenField($model, 'pid'); ?>

<?php $this->endWidget(); ?>