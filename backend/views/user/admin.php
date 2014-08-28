<?php
/** @var $this UserController */
/** @var $model User */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Users') => array('admin'),
	Yii::t('backend', 'Manage'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Create user'), 'url' => array('create')),
);

cs()->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
"); ?>

<h3><?php echo $this->pageTitle; ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); 
?>

    <?
    
    $dateisOn = $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        // 'model'=>$model,
                                    'name' => 'User[date_first]',
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
                                    'name' => 'User[date_last]',
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
       $dateisOn2 = $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        // 'model'=>$model,
                                    'name' => 'User[orders_date_first]',
                                    'language' => 'ru',
                                        'value' => $model->orders_date_first,
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
                                    'name' => 'User[orders_date_last]',
                                    'language' => 'ru',
                                        'value' => $model->orders_date_last,
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


    <?php $this->widget('backend.components.AdminView', array(
        'model' => $model,
        'afterAjaxUpdate'=>"function() {
                                                jQuery('#User_date_first').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                                                jQuery('#User_date_last').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                                                
                                                jQuery('#User_orders_date_first').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                                                jQuery('#User_orders_date_last').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                                                
                                                }",
        'rowHtmlOptionsExpression'=>'array("style"=>$data->order_count>5 ? "color:red" : "")',
        'columns' => array(
            array(
                'value' => '$row+1',
            ),
            'id',
            'email',
            'name',
            'servicename',
            'camefrom',
            'order_count',
            /*'first_buy',*/
            array(
                'name'=>'first_buy',
                'filter'=>$dateisOn,
                'value'=>'$data->first_buy'
                ),
            array(
                'name'=>'orders_by_period',
                'filter'=>$dateisOn2,
                'value'=>'$data->orders_by_period'
                ),
            'orders_by_period',
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>