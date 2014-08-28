<?php
/** @var $model LoginForm */
/** @var $form TbActiveForm */
$this->pageTitle = Yii::app()->name.' - '.Yii::t('backend', 'Login');
?>

<div class="span12">

    <?php $form = $this->beginWidget('TbActiveForm', array(
        'id' => 'login-form',
        'type' => 'horizontal',
        'focus' => '#LoginForm_login',
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )); ?>

    <fieldset>
        <legend><?php echo Yii::app()->name; ?></legend>

        <?php echo $form->textFieldRow($model, 'email', array('class' => 'span2')); ?>
        <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span2')); ?>
        <?php echo $form->checkBoxRow($model, 'rememberMe'); ?>

        <div class="form-actions">
            <?php echo CHtml::submitButton(Yii::t('backend', 'Log in'), array('class' => 'btn btn-primary')); ?>
        </div>

    </fieldset>

    <?php $this->endWidget(); ?>
</div>
