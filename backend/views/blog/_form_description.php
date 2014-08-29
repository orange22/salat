<?php
/** @var $this BlogController */
/** @var $model Blog */
/** @var $models Blog[] */
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
        'afterValidate' => 'js:formAfterValidate',
    ),
)); ?>
<div class="row">
    <div class="span6">
        <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
        <?php echo $form->dropDownListRow($model, 'user_id', User::model()->sort('email')->listData()); ?>
        <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
        <?php echo $form->textAreaRow($model, 'preview_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
        <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
        <?php $this->tinymce(CHtml::resolveName($model, $tmp = "detail_text")); ?>
        <?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
        <?php $this->widget('backend.extensions.calendar.SCalendar', array(
            'inputField' => CHtml::activeId($model, 'date_create'),
            'ifFormat' => '%Y-%m-%d %H:%M:%S',
            'showsTime' => true,
            'language' => 'ru-UTF',
        )); ?>
        <?php echo $form->textFieldRow($model, 'views', array('class' => 'span2')); ?>
        <?php echo $form->textFieldRow($model, 'likes', array('class' => 'span2')); ?>
        <?php echo $form->textFieldRow($model, 'comments', array('class' => 'span2')); ?>
        <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>]
        <?php echo $form->checkBoxRow($model, 'status'); ?>
    </div>
    <div class="span6">
        <div data-block="sjstp3" class="control-group control-group-multiple product-info">
            <div class="control-head">
                    <span class="span3 sizeField">
                        <?php echo Yii::t('backend', 'Dishes'); ?>
                    </span>
            </div>

            <?php $BlogDish = array_pad((array)$model->blogDishes, 1, new BlogDish()); ?>
            <?php $BlogDishCount = count($BlogDish); ?>
            <?php foreach($BlogDish as $i => $item) { /** @var $item ProductInfo */?>

                <div class="controls controls-multiple-row">
                    <?php echo $form->dropDownList($item, "[{$i}]dish_id", CHTML::listData(Dish::model()->active()->sort('t.title')->findAllByAttributes(array('dishgroup_id'=>1)),'id','title'), array(
                        'class' => 'span3 validate-unique',
                        'placeholder' => Yii::t('backend', 'Dish'),
                        'empty'=>''
                    )); ?>


                    <div class="arrayControls">
                        <a href="#" class="btn btn-mini btnRemoveArrayRow"
                            <?php /*if($BlogDishCount == 1) { ?> style="display: none;"<?php } */?>>-</a>
                        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                            <?php if($i < $BlogDishCount - 1) { ?> style="display: none;"<?php }?>
                           data-array-last="<?php echo $i; ?>">+</a>
                    </div>

                    <?php echo $form->error($item, "[{$i}]dish_id"); ?>
                </div>

            <?php } ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/html" id="sjstp3">
    <?php echo $form->dropDownList($BlogDish[0], "[<%=idx%>]dish_id", CHTML::listData(Dish::model()->active()->sort('t.title')->findAllByAttributes(array('dishgroup_id'=>1)),'id','title'), array(
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
    <?php echo $form->error($BlogDish[0], '[<%=idx%>]dish_id', array('encode' => false)); ?>
</script>
<?php cs()->registerScriptFile('/backend/js/simple_js_templating.js', CClientScript::POS_END); ?>

