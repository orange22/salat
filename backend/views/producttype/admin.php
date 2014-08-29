<?php
/** @var $this DishtypeController */
/** @var $model Dishtype */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Dishtypes') => array('admin'),
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
           /*
            array(
                           'name' => 'image_id',
                           'value' => '$data->image ? $data->image->title : null',
                           'filter' => CHtml::listData(Image::model()->findAll(), 'id', 'title'),
                       ),*/
           
            'status',
            'sort',
        ),
    )); ?>

<?php $this->endWidget(); ?>