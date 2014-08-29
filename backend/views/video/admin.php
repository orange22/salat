<?php
/** @var $this VideoController */
/** @var $model Video */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Videos') => array('admin'),
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
            'url',
            array(
                'name' => 'videotype_id',
                'value' => '$data->videotype ? $data->videotype->title : null',
                'filter' => CHtml::listData(Videotype::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'course_id',
                'value' => '$data->course ? $data->course->title : null',
                'filter' => CHtml::listData(Course::model()->findAll(), 'id', 'title'),
            ),
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>