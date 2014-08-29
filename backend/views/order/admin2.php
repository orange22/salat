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

    
    
    
    <?
    
    $dateisOn = $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        // 'model'=>$model,
                                    'name' => 'Order[date_first]',
                                    'language' => 'ru',
                                        'value' => $model->date_first,
                                    // additional javascript options for the date picker plugin
                                    'options'=>array(
                                        'showAnim'=>'fold',
                                        'dateFormat'=>'yy-mm-dd',
                                        'changeMonth' => 'true',
                                        'changeYear'=>'true',
                                        'constrainInput' => 'false',
                                    ),
                                    'htmlOptions'=>array(
                                        'style'=>'height:20px;width:70px;',
                                    ),
// DONT FORGET TO ADD TRUE this will create the datepicker return as string
                                ),true) . ' по ' . $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        // 'model'=>$model,
                                    'name' => 'Order[date_last]',
                                    'language' => 'ru',
                                        'value' => $model->date_last,
                                    // additional javascript options for the date picker plugin
                                    'options'=>array(
                                        'showAnim'=>'fold',
                                        'dateFormat'=>'yy-mm-dd',
                                        'changeMonth' => 'true',
                                        'changeYear'=>'true',
                                        'constrainInput' => 'false',
                                    ),
                                    'htmlOptions'=>array(
                                        'style'=>'height:20px;width:70px',
                                    ),
// DONT FORGET TO ADD TRUE this will create the datepicker return as string
                                ),true);
    
    
    ?>
    
    

    
    <?php 
    $this->widget('backend.components.AdminView', array(
        'model' => $model,
        'afterAjaxUpdate'=>"function() {
                                                jQuery('#Order_date_first').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                                                jQuery('#Order_date_last').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                                                }",
        'rowHtmlOptionsExpression'=>'array("style"=>$data->orderstate_id == 4 ? "color:green; font-weight:bold" : "")',
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
            'camefrom',
            array(
            'name' => 'orderstate_id',
            'value' => '$data->orderstate_id>0 ? $data->orderstate->title : null',
            'filter' => CHtml::listData(Orderstate::model()->findAll(), 'id', 'title'),
            ),
           /*
            array(
                           'class' => 'bootstrap.widgets.TbToggleColumn',
                           'toggleAction' => 'example/toggle',
                           'name' => 'status',
                           'header' => 'Toggle'
                       ),*/

            'order_count',
            'dish_count',
            array(
                'name'=>'dishlist',
                'value'=>'$data->dishlist',
                ),
            'drink_count',
             array(
                'name'=>'drinklist',
                'value'=>'$data->drinklist',
                ),
            'name',
            'title',
            'phone',
            'total',
            array(
                'value'=>'$data->orderCharities[0]->charity->value',
                'htmlOptions'=>array('style'=>'color:red'),
            ),
            array(
                'name'=> 'delivery',
                'value' => 'implode(" - ",array($data->delivery_from,$data->delivery_till))',
                'filter' => ''
                ),
            'delivery_addr',
            /*
            array(
                          'name' => 'date_create',
                          'filter' => $this->widget('backend.extensions.bootstrap.widgets.TbDateRangePicker',
                           array('name'=>'Order[name]')
                           ),
                       ),*/

             array(
                'name'=>'date_create',
                'filter'=>$dateisOn,
                'value'=>'$data->date_create'
                ),
             
            
        ),
        'extendedSummary' => array(
                'title' => 'Всего заказано на сумму',
                'columns' => array(
                    'total' => array('label'=>'Всего', 'class'=>'TbSumOperation')
                )
            ),
    )); ?>

<?php $this->endWidget(); ?>