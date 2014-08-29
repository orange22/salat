<?php
/** @var $this CourseController */
/** @var $model Course */
/** @var $models Course[] */
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
    <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
    <?php echo $form->textAreaRow($model, 'preview_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php $this->tinymce(CHtml::resolveName($model, $tmp = "detail_text")); ?>
    <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
    <?php echo $form->fileUploadRow($model, 'recipeimage_id', 'recipeimage'); ?>
    <?php echo $form->textFieldRow($model, 'calories', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'weight', array('class' => 'span2')); ?>
    <?php echo $form->dropDownListRow($model, 'dishtype_id', Dishtype::model()->listData(), array('empty'=>'')); ?>
    <?php echo $form->dropDownListRow($model, 'dish_id', Dish::model()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'recipe'); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>
    </div>
    <div class="span6">
    	 	<div data-block="sjstpl" class="control-group control-group-multiple product-info">
                <div class="control-head">
                    <span class="span3 sizeField">
                        <?php echo Yii::t('backend', 'Ingredient'); ?>
                    </span>
                    <span class="span3"><?php echo Yii::t('backend', 'Value'); ?></span>
                    
                </div>
                <!--<label for="Ingredient_0_ingredient_id" class="control-label"><?php echo Yii::t('backend', 'Ingredient'); ?></label>-->

                <?php 
                $CourseIngredientCount1=count($model->courseIngredients);
                $CourseIngredient = array_pad((array)$model->courseIngredients, 1, new CourseIngredient()); 
                ?>
                <?php $CourseIngredientCount = count($CourseIngredient); ?>
				<?php foreach($CourseIngredient as $i => $item) { /** @var $item ProductInfo */?>
                    
                    
                    
                    
                    <div class="controls controls-multiple-row">
                        <? if($CourseIngredientCount1>0){?>
                        <select disabled="disabled" class="span3 validate-unique" placeholder="Ингредиент" >
                            <option value="<?=$item->value;?>"><?=$item->ingredient->title;?></option>
                        </select> 
                        <input type="hidden" value="<?=$item->ingredient_id;?>" name="CourseIngredient[<?=$i;?>][ingredient_id]" id="CourseIngredient_<?=$i;?>_ingredient_id"/>
                        <input type="hidden" value="<?=$item->value;?>" class="span3" placeholder="Значение" name="CourseIngredient[<?=$i;?>][value]" id="CourseIngredient_<?=$i;?>_value">
                        <?php 
                        //CVarDumper::dump($item->ingredient->title,10,true);
                         
                        /*echo $form->dropDownList($item, "[{$i}]ingredient_id", Ingredient::model()->listData(), array(
                            'class' => 'span3 validate-unique',
                            'placeholder' => Yii::t('backend', 'Ingredient'),
                        )); 
                        */
                        ?>
                        <?php echo $form->textField($item, "[{$i}]value", array(
                            'value' => $item->getIsNewRecord() ? '' : $item->value,
                            'class' => 'span3',
                            'disabled' => 'disabled',
                            'placeholder' => Yii::t('backend', 'Value'),
                        )); ?>
                        <?}else{
                            echo $form->dropDownList($item, "[{$i}]ingredient_id", Ingredient::model()->listData(), array(
                            'class' => 'span3 validate-unique',
                            'placeholder' => Yii::t('backend', 'Ingredient'),
                        )); 
                        echo $form->textField($item, "[{$i}]value", array(
                            'value' => $item->getIsNewRecord() ? '' : $item->value,
                            'class' => 'span3',
                            'placeholder' => Yii::t('backend', 'Value'),
                        ));
                        }?>
                        <div class="arrayControls">
                            <a href="#" class="btn btn-mini btnRemoveArrayRow"
                                <?php /*if($CourseIngredientCount == 1) { ?> style="display: none;"<?php } */?>>-</a>
                            <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                                <?php if($i < $CourseIngredientCount - 1) { ?> style="display: none;"<?php }?>
                               data-array-last="<?php echo $i; ?>">+</a>
                        </div>

                        <?php echo $form->error($item, "[{$i}]ingredient_id"); ?>
                        <?php echo $form->error($item, "[{$i}]value"); ?>
                    </div>

                <?php } ?>
            </div>
  	</div>
</div>
<?php $this->endWidget(); ?>
<script type="text/html" id="sjstpl">
    <?php echo $form->dropDownList($CourseIngredient[0], "[<%=idx%>]ingredient_id", Ingredient::model()->listData(), array(
        'value' => '',
        'encode' => false,
        'class' => 'span3 validate-unique',
        'placeholder' => Yii::t('backend', 'Ingredient'),
        'empty'=>'test'
    )); ?>
    <?php echo $form->textField($CourseIngredient[0], '[<%=idx%>]value', array(
        'value' => '',
        'encode' => false,
        'class' => 'span3',
        'placeholder' => Yii::t('backend', 'Value')
    )); ?>

    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>

    
    <?php echo $form->error($CourseIngredient[0], '[<%=idx%>]ingredient_id', array('encode' => false)); ?>
    <?php echo $form->error($CourseIngredient[0], '[<%=idx%>]value', array('encode' => false)); ?>
</script>

<?php cs()->registerScriptFile('/backend/js/simple_js_templating.js', CClientScript::POS_END); ?>