<?php
/** @var $this FileController */
/** @var $model File */
/** @var $form ActiveForm */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('backend', 'Files') => array('admin'),
    Yii::t('backend', 'Manage'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('file-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h3><?php echo Yii::t('backend', 'Manage files'); ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>

<?php $this->widget('backend.components.AdminView', array(
    'model' => $model,
    'actionButtons' => array('delete',
        CHtml::linkButton(Yii::t('backend', 'Delete unused'), array(
            'csrf' => true,
            'submit' => array('deleteUnused'),
            'class' => 'btn btn-danger btnDeleteUnused',
            'confirm' => Yii::t('backend', 'Are you sure want to delete unused files?'),
        )),
    ),
    'buttonColumn' => array(
        'template' => '{update} {delete}',
    ),
    'columns' => array(
        'id',
        'file',
        'path',
        'width',
        'height',
        array(
            'class' => 'CLinkColumn',
            'header' => Yii::t('backend', 'Link'),
            'label' => Yii::t('backend', 'Download'),
            'urlExpression' => 'Yii::app()->params["siteUrl"]."/{$data->path}/{$data->file}"',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            )
        ),
    ),
)); ?>

<?php $this->endWidget(); ?>