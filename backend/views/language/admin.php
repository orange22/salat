<?php
/** @var $this LanguageController */
/** @var $model Language */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Languages') => array('admin'),
	Yii::t('backend', 'Manage'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Create language'), 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('language-grid', {
        data: $(this).serialize()
    });
    return false;
});
"); ?>

<h3><?php echo $this->pageTitle; ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>

    <?php $this->widget('backend.components.AdminView', array(
        'model' => $model,
        'columns' => array(
            'id',
            'title',
            'title_alt',
            'public',
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>