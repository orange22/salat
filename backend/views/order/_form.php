<?php
/** @var $this OrderController */
/** @var $model Order */
/** @var $models Order[] */
/** @var $form ActiveForm */
?>

<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'model' => $model,
    'fieldsetLegend' => $legend,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        //'afterValidate' => 'js:formAfterValidate',
        'afterValidate' => 'js:function($form, data, hasError) {
            return checkSizeUnique();
        }',
    ),
)); ?>
<div class="row">
    <div class="span6">
        <? if(!isset($model->date_create))
            $model->date_create=date('Y-m-d H:i:s');
        ?>

        <?php echo $form->dropDownListRow($model, 'user_id', User::model()->sort('email')->listData(),array('empty'=>'Новый пользователь')); ?>
        <?php echo $form->dropDownListRow($model, 'paytype_id', Paytype::model()->listData()); ?>
        <?php echo $form->dropDownListRow($model, 'orderstate_id', Orderstate::model()->listData()); ?>
        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span9', 'maxlength' => 255)); ?>
        <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
        <?php echo $form->textFieldRow($model, 'phone', array('class' => 'span9', 'maxlength' => 55)); ?>
        <?php echo $form->dropDownListRow($model, 'deliveryplace_id', Deliveryplace::model()->active()->sort()->listData()); ?>
        <?php echo $form->textFieldRow($model, 'delivery_from', array('class' => 'span9', 'maxlength' => 55)); ?>
        <?php echo $form->textFieldRow($model, 'delivery_till', array('class' => 'span9', 'maxlength' => 55)); ?>
        <?php echo $form->textAreaRow($model, 'delivery_addr', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
        <?php echo $form->textFieldRow($model, 'camefrom', array('class' => 'span9', 'maxlength' => 255)); ?>
        <?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span4', 'value'=>$model->date_create)); ?>
        <?php $this->widget('backend.extensions.calendar.SCalendar', array(
            'inputField' => CHtml::activeId($model, 'date_create'),
            'ifFormat' => '%Y-%m-%d %H:%M:%S',
            'showsTime' => true,
            'language' => 'ru-UTF',
        )); ?>
        <?php echo $form->dropDownListRow($model, 'discount_id',Discount::model()->listData(), array('empty'=>'')); ?>
        <?
        $charityOrders=CharityOrder::model()->findAllByAttributes(array('order_id'=>$model->id));
        $charityArr=array();
        foreach($charityOrders as $char){
            $charityArr[$char->charity_id]=$char->charity_id;
        }
        $charities=Charity::model()->active()->sort()->findAll();

        foreach($charities as $charity){
            $cheched=(in_array($charity->id,$charityArr))?' checked="checked"':'';
            echo '
            <div class="control-group ">
                <div class="controls">
                    <label class="checkbox" for="CharityOrder_'.$charity->id.'">
                        <input name="CharityOrder['.$charity->id.']" id="CharityOrder_'.$charity->id.'" value="'.$charity->id.'"'.$cheched.' type="checkbox">
                        '.$charity->title.'
                    </label>
                </div>
            </div>
            ';
        }
        ?>
        <?php echo $form->textFieldRow($model, 'total', array('class' => 'span9', 'maxlength' => 55)); ?>

        <?php echo $form->checkBoxRow($model, 'status'); ?>
        <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    </div>
    <div class="span6">
        <div data-block="sjstpl" class="control-group control-group-multiple product-info">
            <div class="control-head">
                    <span class="span6 sizeField">
                        <?php echo Yii::t('backend', 'Dish'); ?>
                    </span>
                <span class="span3"><?php echo Yii::t('backend', 'Quantity'); ?></span>

            </div>
            <!--<label for="Dish_0_dish_id" class="control-label"><?php echo Yii::t('backend', 'Dish'); ?></label>-->

            <?php $OrderDish = array_pad((array)$model->orderDishes, 1, new OrderDish()); ?>
            <?php $OrderDishCount = count($OrderDish); ?>
            <?php foreach($OrderDish as $i => $item) { /** @var $item ProductInfo */?>

                <div class="controls controls-multiple-row">
                    <?php echo $form->dropDownList($item, "[{$i}]dish_id", Dish::model()->sort('status DESC')->listData(), array(
                        'class' => 'span6 validate-unique',
                        'placeholder' => Yii::t('backend', 'Dish'),
                        'empty'=>''
                    )); ?>
                    <?php echo $form->textField($item, "[{$i}]quantity", array(
                        'quantity' => $item->getIsNewRecord() ? '' : $item->quantity,
                        'class' => 'span3',
                        'placeholder' => Yii::t('backend', 'Quantity'),
                    )); ?>

                    <div class="arrayControls">
                        <a href="#" class="btn btn-mini btnRemoveArrayRow"
                            <?php /*if($OrderDishCount == 1) { ?> style="display: none;"<?php } */?>>-</a>
                        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                            <?php if($i < $OrderDishCount - 1) { ?> style="display: none;"<?php }?>
                           data-array-last="<?php echo $i; ?>">+</a>
                    </div>

                    <?php echo $form->error($item, "[{$i}]dish_id"); ?>
                    <?php echo $form->error($item, "[{$i}]quantity"); ?>
                </div>

            <?php } ?>
        </div>
        <div data-block="sjstpl2" class="control-group control-group-multiple drink-info">

            <div class="control-head">
                    <span class="span6 sizeField">
                        <?php echo Yii::t('backend', 'Drink'); ?>
                    </span>
                <span class="span3"><?php echo Yii::t('backend', 'Quantity'); ?></span>

            </div>
            <!--<label for="Dish_0_dish_id" class="control-label"><?php echo Yii::t('backend', 'Dish'); ?></label>-->

            <?php $OrderDrink = array_pad((array)$model->orderDrinks, 1, new OrderDrink()); ?>
            <?php $OrderDrinkCount = count($OrderDrink); ?>
            <?php foreach($OrderDrink as $i => $item) { /** @var $item ProductInfo */?>

                <div rel="sjstpl2" class="controls controls-multiple-row">
                    <?php echo $form->dropDownList($item, "[{$i}]drink_id", Drink::model()->sort('status DESC')->listData(), array(
                        'class' => 'span6 validate-unique',
                        'placeholder' => Yii::t('backend', 'Dish'),
                        'empty'=>''
                    )); ?>
                    <?php echo $form->textField($item, "[{$i}]quantity", array(
                        'quantity' => $item->getIsNewRecord() ? '' : $item->quantity,
                        'class' => 'span3',
                        'placeholder' => Yii::t('backend', 'Quantity'),
                    )); ?>

                    <div class="arrayControls">
                        <a href="#" class="btn btn-mini btnRemoveArrayRow"
                            <?php /*if($OrderDishCount == 1) { ?> style="display: none;"<?php } */?>>-</a>
                        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                            <?php if($i < $OrderDrinkCount - 1) { ?> style="display: none;"<?php }?>
                           data-array-last="<?php echo $i; ?>">+</a>
                    </div>

                    <?php echo $form->error($item, "[{$i}]drink_id"); ?>
                    <?php echo $form->error($item, "[{$i}]quantity"); ?>
                </div>

            <?php } ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/html" id="sjstpl">
    <?php echo $form->dropDownList($OrderDish[0], "[<%=idx%>]dish_id", Dish::model()->sort('status DESC')->listData(), array(
        'quantity' => '',
        'encode' => false,
        'class' => 'span6 validate-unique',
        'placeholder' => Yii::t('backend', 'Dish'),
        'empty'=>''
    )); ?>
    <?php echo $form->textField($OrderDish[0], '[<%=idx%>]quantity', array(
        'quantity' => '',
        'encode' => false,
        'class' => 'span3',
        'placeholder' => Yii::t('backend', 'Quantity'),
        'value'=>'2'
    )); ?>

    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>

    
    <?php echo $form->error($OrderDish[0], '[<%=idx%>]dish_id', array('encode' => false)); ?>
    <?php echo $form->error($OrderDish[0], '[<%=idx%>]quantity', array('encode' => false)); ?>
</script>
<script type="text/html" id="sjstpl2">
    <?php echo $form->dropDownList($OrderDrink[0], "[<%=idx%>]drink_id", Drink::model()->sort('status DESC')->listData(), array(
        'quantity' => '',
        'encode' => false,
        'class' => 'span6 validate-unique',
        'placeholder' => Yii::t('backend', 'Drink'),
        'empty'=>''
    )); ?>
    <?php echo $form->textField($OrderDrink[0], '[<%=idx%>]quantity', array(
        'quantity' => '',
        'encode' => false,
        'class' => 'span3',
        'placeholder' => Yii::t('backend', 'Quantity')
    )); ?>

    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>

    
    <?php echo $form->error($OrderDrink[0], '[<%=idx%>]drink_id', array('encode' => false)); ?>
    <?php echo $form->error($OrderDrink[0], '[<%=idx%>]quantity', array('encode' => false)); ?>
</script>

<?php cs()->registerScriptFile('/backend/js/simple_js_templating.js', CClientScript::POS_END); ?>
