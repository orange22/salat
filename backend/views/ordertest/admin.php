<?php
/** @var $this OrderController */
/** @var $model Order */
/** @var $form CActiveForm */
?>
<?php

$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Orders') => array('admin'),
	Yii::t('backend', 'Manage'),
);
?>

<h3><?php echo $this->pageTitle; ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>

    <?php 
    
    $this->widget('backend.components.AdminView', array(
        'model' => $model,
        'columns' => array(
        	array(
                'value' => '$row+1',
            ),
            'id',
            array(
                'name' => 'user_id',
                'value' => '$data->user ? $data->user->email : null',
                'filter' => CHtml::listData(User::model()->sort('t.email asc')->findAll(), 'id', 'email'),
            ),
            'order_count',
            'dish_count',
            'drink_count',
            'name',
            'title',
            'phone',
            'delivery_from',
            'delivery_till',
            'delivery_addr',
	       /*
			array(
						  'name' => 'date_create',
						  'filter' => $this->widget('backend.extensions.bootstrap.widgets.TbDateRangePicker',
						   array('name'=>'Order[name]')
						   ),
					   ),*/
		   
            'status',
            
        ),
    )); ?>

<?php $this->endWidget(); ?>