<?php
/** @var $this DishController */
/** @var $model Dish */
/** @var $models Dish[] */
/** @var $form ActiveForm */
?>

<?php /*
$form = $this->beginWidget('backend.components.ActiveForm', array(
    'model' => $model,
    'fieldsetLegend' => $legend,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:formAfterValidate',
    ),
));*/
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
        'afterValidate' => 'js:function($form, data, hasError) {
            return true;
        }',
    ),
)); ?>

<div class="row">
    <div class="span6">
        <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
        <?php echo $form->dropDownListRow($model, 'dishtype_id', Dishtype::model()->listData()); ?>
        <?php echo $form->dropDownListRow($model, 'difficulty', array(1=>1,2=>2,3=>3)); ?>
        <?php echo $form->dropDownListRow($model, 'shef_id', CHtml::listData(User::model()->with('userUsertypes')->findAll('userUsertypes.id=2'),'id','name'), array('empty' => '')); ?>
        <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
        <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
        <?php echo $form->textAreaRow($model, 'video', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
        <?php echo $form->dropDownListRow($model, 'persons', array(1=>1,2=>2,3=>3,4=>4)); ?>
        <?php $this->tinymce(CHtml::resolveName($model, $tmp = "detail_text")); ?>
        <?php echo $form->textFieldRow($model, 'prepare', array('class' => 'span9')); ?>
        <?php echo $form->textFieldRow($model, 'weight', array('class' => 'span2')); ?>
        <?php echo $form->dropDownListRow($model, 'cookware_1_id', Cookware::model()->listData()); ?>
        <?php echo $form->textFieldRow($model, 'cookware_1_num', array('class' => 'span2')); ?>
        <?php echo $form->dropDownListRow($model, 'cookware_2_id', Cookware::model()->listData()); ?>
        <?php echo $form->textFieldRow($model, 'cookware_2_num', array('class' => 'span2')); ?>
        <?php echo $form->textFieldRow($model, 'price', array('class' => 'span2')); ?>
        <?php echo $form->textFieldRow($model, 'steps', array('class' => 'span2')); ?>
        <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
        <?php echo $form->checkBoxRow($model, 'topsell'); ?>
        <?php echo $form->checkBoxRow($model, 'new'); ?>
        <?php echo $form->checkBoxRow($model, 'status'); ?>
        <?php echo $form->checkBoxRow($model, 'main'); ?>
    </div>
    <div class="span6">
        <div data-block="sjstpl" class="control-group control-group-multiple product-info">
            <div class="control-head">
                    <span class="span3 sizeField">
                        <?php echo Yii::t('backend', 'Drink'); ?>
                    </span>
            </div>
            <!--<label for="Drink_0_drink_id" class="control-label"><?php echo Yii::t('backend', 'Drink'); ?></label>-->

            <?php $DrinkDish = array_pad((array)$model->drinkDishes, 1, new DrinkDish()); ?>
            <?php $DrinkDishCount = count($DrinkDish); ?>
            <?php foreach($DrinkDish as $i => $item) { /** @var $item ProductInfo */?>

                <div class="controls controls-multiple-row">
                    <?php echo $form->dropDownList($item, "[{$i}]drink_id", Drink::model()->active()->listData(), array(
                        'class' => 'span3 validate-unique',
                        'placeholder' => Yii::t('backend', 'Drink'),
                        'empty'=>''
                    )); ?>


                    <div class="arrayControls">
                        <a href="#" class="btn btn-mini btnRemoveArrayRow"
                            <?php /*if($DrinkDishCount == 1) { ?> style="display: none;"<?php } */?>>-</a>
                        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                            <?php if($i < $DrinkDishCount - 1) { ?> style="display: none;"<?php }?>
                           data-array-last="<?php echo $i; ?>">+</a>
                    </div>

                    <?php echo $form->error($item, "[{$i}]drink_id"); ?>
                    <?php echo $form->error($item, "[{$i}]value"); ?>
                </div>

            <?php } ?>
        </div>
        <div data-block="sjstp2" class="control-group control-group-multiple product-info">
            <div class="control-head">
                    <span class="span3 sizeField">
                        <?php echo Yii::t('backend', 'Portions'); ?>
                    </span>
            </div>
            <!--<label for="Drink_0_drink_id" class="control-label"><?php echo Yii::t('backend', 'Drink'); ?></label>-->

            <?php $Portion = array_pad((array)$model->portions, 1, new Portion()); ?>
            <?php $PortionCount = count($Portion); ?>
            <?php foreach($Portion as $i => $item) { /** @var $item ProductInfo */?>

                <div class="controls controls-multiple-row">
                    <?php echo $form->textField($item, "[{$i}]value", array(
                        'value' => $item->getIsNewRecord() ? '' : $item->value,
                        'class' => 'span3',
                        'placeholder' => Yii::t('backend', 'Value'),
                    )); ?>

                    <div class="arrayControls">
                        <a href="#" class="btn btn-mini btnRemoveArrayRow"
                            <?php /*if($PortionCount == 1) { ?> style="display: none;"<?php } */?>>-</a>
                        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                            <?php if($i < $PortionCount - 1) { ?> style="display: none;"<?php }?>
                           data-array-last="<?php echo $i; ?>">+</a>
                    </div>
                    <?php echo $form->error($item, "[{$i}]value"); ?>
                </div>

            <?php } ?>
        </div>
        <div data-block="sjstp3" class="control-group control-group-multiple product-info">
            <div class="control-head">
                    <span class="span3 sizeField">
                        <?php echo Yii::t('backend', 'Similar Dishes'); ?>
                    </span>
            </div>

            <?php $DishSimilar = array_pad((array)$model->dishSimilar, 1, new DishSimilar()); ?>
            <?php $DishSimilarCount = count($DishSimilar); ?>
            <?php foreach($DishSimilar as $i => $item) { /** @var $item ProductInfo */?>

                <div class="controls controls-multiple-row">
                    <?php echo $form->dropDownList($item, "[{$i}]similar_id", CHTML::listData(Dish::model()->findAllByAttributes(array('dishgroup_id'=>1)),'id','title'), array(
                        'class' => 'span3 validate-unique',
                        'placeholder' => Yii::t('backend', 'Drink'),
                        'empty'=>''
                    )); ?>


                    <div class="arrayControls">
                        <a href="#" class="btn btn-mini btnRemoveArrayRow"
                            <?php /*if($DishSimilarCount == 1) { ?> style="display: none;"<?php } */?>>-</a>
                        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                            <?php if($i < $DishSimilarCount - 1) { ?> style="display: none;"<?php }?>
                           data-array-last="<?php echo $i; ?>">+</a>
                    </div>

                    <?php echo $form->error($item, "[{$i}]drink_id"); ?>
                </div>

            <?php } ?>
        </div>
        <div data-block="sjstp4" class="control-group control-group-multiple product-info">
            <div class="control-head">
                    <span class="span3 sizeField">
                        <?php echo Yii::t('backend', 'Tools'); ?>
                    </span>
            </div>

            <?php $DishTool = array_pad((array)$model->dishTools, 1, new DishTool()); ?>
            <?php $DishToolCount = count($DishTool); ?>
            <?php foreach($DishTool as $i => $item) { /** @var $item ProductInfo */?>

                <div class="controls controls-multiple-row">
                    <?php echo $form->dropDownList($item, "[{$i}]tool_id", CHTML::listData(Dish::model()->findAllByAttributes(array('dishgroup_id'=>2)),'id','title'), array(
                        'class' => 'span3 validate-unique',
                        'placeholder' => Yii::t('backend', 'Tool'),
                        'empty'=>''
                    )); ?>


                    <div class="arrayControls">
                        <a href="#" class="btn btn-mini btnRemoveArrayRow"
                            <?php /*if($DishToolCount == 1) { ?> style="display: none;"<?php } */?>>-</a>
                        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                            <?php if($i < $DishToolCount - 1) { ?> style="display: none;"<?php }?>
                           data-array-last="<?php echo $i; ?>">+</a>
                    </div>

                    <?php echo $form->error($item, "[{$i}]drink_id"); ?>
                </div>

            <?php } ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/html" id="sjstpl">
    <?php echo $form->dropDownList($DrinkDish[0], "[<%=idx%>]drink_id", Drink::model()->active()->listData(), array(
        'value' => '',
        'encode' => false,
        'class' => 'span3 validate-unique',
        'placeholder' => Yii::t('backend', 'Drink'),
        'empty'=>''
    )); ?>
    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>
    <?php echo $form->error($DrinkDish[0], '[<%=idx%>]drink_id', array('encode' => false)); ?>
</script>
<script type="text/html" id="sjstp2">
    <?php echo $form->textField($Portion[0], '[<%=idx%>]value', array(
        'value' => '',
        'encode' => false,
        'class' => 'span3',
        'placeholder' => Yii::t('backend', 'Value')
    )); ?>

    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>
    <?php echo $form->error($Portion[0], '[<%=idx%>]drink_id', array('encode' => false)); ?>
</script>
<script type="text/html" id="sjstp3">
    <?php echo $form->dropDownList($DishSimilar[0], "[<%=idx%>]similar_id", CHTML::listData(Dish::model()->findAllByAttributes(array('dishgroup_id'=>1)),'id','title'), array(
        'value' => '',
        'encode' => false,
        'class' => 'span3 validate-unique',
        'placeholder' => Yii::t('backend', 'Dish'),
        'empty'=>''
    )); ?>
       <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>
    <?php echo $form->error($DishSimilar[0], '[<%=idx%>]similar_id', array('encode' => false)); ?>
</script>
<script type="text/html" id="sjstp4">
    <?php echo $form->dropDownList($DishTool[0], "[<%=idx%>]tool_id", CHTML::listData(Dish::model()->findAllByAttributes(array('dishgroup_id'=>2)),'id','title'), array(
        'value' => '',
        'encode' => false,
        'class' => 'span3 validate-unique',
        'placeholder' => Yii::t('backend', 'Dish'),
        'empty'=>''
    )); ?>
    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>
    <?php echo $form->error($DishTool[0], '[<%=idx%>]tool_id', array('encode' => false)); ?>
</script>
<?php cs()->registerScriptFile('/backend/js/simple_js_templating.js', CClientScript::POS_END); ?>
