<?php
/** @var $this UserController */
/** @var $model User */
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
   	<?php echo $form->textFieldRow($model, 'name', array('class' => 'span4', 'maxlength' => 64)); ?>
    <?php echo $form->textFieldRow($model, 'servicename', array('class' => 'span4', 'maxlength' => 64)); ?>
    <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
    <?php echo $form->fileUploadRow($model, 'signature_id', 'signature'); ?>
    <?php /* echo $form->dropDownListRow($model, 'language_id', Language::getList(), array('empty' => Yii::t('backend', ' - Not selected - '))); */?>
    <?
    /*php if($model->type == User::ADMIN) { ?>
        <?php echo $form->textFieldRow($model, 'login', array('class' => 'span4', 'maxlength' => 32, 'autocomplete' => 'off')); ?>
    <?php } */?>
    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span4', 'value' => '', 'autocomplete' => 'off')); ?>
    <?php echo $form->textFieldRow($model, 'email', array('class' => 'span4', 'maxlength' => 32)); ?>
    <?php echo $form->textFieldRow($model, 'position', array('class' => 'span4', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'discount', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'phone', array('class' => 'span4', 'maxlength' => 64)); ?>
    <?php echo $form->textAreaRow($model, 'delivery_addr', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'camefrom', array('class' => 'span4', 'maxlength' => 64)); ?>
    <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php /*if($model->type == User::ADMIN) {
    	 echo $form->dropDownListRow($model, 'authItems', User::getRoleList(), array(
            'multiple' => 'multiple',
            'key' => 'name',
        ));
	} */?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>
  	</div>
  	<div class="span6">
  		
  		
  		
  		 <?php echo $form->dropDownListRow($model, 'userUsertypes', Usertype::model()->listData(), array(
                'size' => 13,
                'multiple' => true,
            )); ?>
  	</div>
</div>
<?php $this->endWidget(); ?>