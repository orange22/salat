<?php
/** @var $this StepController */
/** @var $model Step */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Steps') => array('admin'),
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
            'step',
            'status',
            'sort',
            array(
                'name' => 'course_id',
                'value' => '$data->course ? $data->course->title : null',
                'filter' => Course::model()->listData(),
            ),
        ),
    )); ?>

<?php $this->endWidget(); ?>