<?php
/** @var $this GalleryItemController */
/** @var $model GalleryItem */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Gallery Images') => array('admin'),
	Yii::t('backend', 'Manage'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Create gallery image'), 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('gallery-item-grid', {
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
            array(
                'name' => 'post_pid',
                'value' => '$data->post ? $data->post->title : null',
                'filter' => CHtml::listData(Post::model()->listData(), 'pid', 'title'),
            ),
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>