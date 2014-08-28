<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'formActions' => array(
        CHtml::submitButton(Rights::t('core', 'Assign'), array(
            'class' => 'btn btn-primary',
            'name' => 'apply',
        ))
    )
)); ?>

<?php echo $form->dropDownListRow($model, 'itemname', $itemnameSelectOptions); ?>

<?php $this->endWidget(); ?>