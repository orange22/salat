<?php
/** @var $this DiscountController */
/** @var $model Discount */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Discounts') => array('admin'),
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
            'discount',
            'disccode',
            array(
                'name' => 'user_id',
                'value' => '$data->user ? $data->user->email : null',
                'filter' => CHtml::listData(User::model()->findAll(), 'id', 'email'),
            ),
            array(
                'name' => 'discounttype_id',
                'value' => '$data->discounttype ? $data->discounttype->title : null',
                'filter' => CHtml::listData(Discounttype::model()->findAll(), 'id', 'title'),
            ),
            'activations',
            'date_end',
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>