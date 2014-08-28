<?php
/** @var $this ProductController */
/** @var $model Product */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Products') => array('admin'),
	Yii::t('backend', 'Manage'),
);
?>

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
            array(
                'name' => 'category_id',
                'value' => '$data->category ? $data->category->title : null',
                'filter' => CHtml::listData(Category::model()->findAll(), 'id', 'title'),
            ),
            'detail_text',
            'price',
            'weight',
            'date_create',
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>