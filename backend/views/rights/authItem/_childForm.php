<div class="form">

    <?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'formActions' => false
)); ?>

    <?php echo $form->dropDownListRow($model, 'itemname', $itemnameSelectOptions); ?>

    <div class="form-actions">
        <?php echo CHtml::submitButton(Rights::t('core', 'Add'), array('class' => 'btn btn-primary')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>