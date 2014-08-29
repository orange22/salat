<?php
/** @var $this CourseController */
/** @var $model Course */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Courses') => array('admin'),
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
            'sort',
            'status',
            'calories',
            array(
                'name' => 'dishtype_id',
                'value' => '$data->coursetype ? $data->coursetype->title : null',
                'filter' => CHtml::listData(Dishtype::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'dish_id',
                'value' => '$data->dish ? $data->dish->title : null',
                'filter' => CHtml::listData(Dish::model()->findAll(), 'id', 'title'),
            ),
        ),
    )); ?>

<?php $this->endWidget(); ?>