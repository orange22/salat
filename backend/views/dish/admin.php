<?php
/** @var $this DishController */
/** @var $model Dish */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Dishes') => array('admin'),
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
            array('name'=>'date_create',
			'value'=>'yii::app()->dateFormatter->format("dd MMMM yyyy h:m:s", "date_create");',
			'filter'=>false),
            'status',
            'sort',
            'price',
            'main',
            array(
                'name' => 'dishtype_id',
                'value' => '$data->dishtype ? $data->dishtype->title : null',
                'filter' => CHtml::listData(Dishtype::model()->findAll(), 'id', 'title'),
            ),
        ),
    )); ?>

<?php $this->endWidget(); ?>