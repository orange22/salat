<?php
/** @var $this DishImageController */
/** @var $model DishImage */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Dish Images') => array('admin'),
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
            array(
                'name' => 'dish_id',
                'value' => '$data->dish ? $data->dish->title : null',
                'filter' => CHtml::listData(Dish::model()->findAll(), 'id', 'file'),
            ),
            'sort',
            array(
                'name' => 'thumb_id',
                'value' => '$data->dish ? $data->thumb->file : null',
                'filter' => CHtml::listData(Dish::model()->findAll(), 'id', 'file'),
            ),
        ),
    )); ?>

<?php $this->endWidget(); ?>