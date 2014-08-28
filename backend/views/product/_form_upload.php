<?php
/** @var $this DishController */
/** @var $model Dish */
/** @var $form ActiveForm */
$uploadifyId = CHtml::getIdByName('DishImage[image_id]');
?>
<h4><?php echo Yii::t('backend', 'Upload images'); ?></h4>
<p class="help-block"><?php echo DishImage::model()->attach->getSizeHint(); ?></p>
<div class="row-fluid">
    <form action="<?php echo $this->createUrl('dish/create'); ?>" method="post">
        <div class="span4">
            <div class="well">
                <?php $this->widget('backend.components..uploadify.UploadifyWidget', array(
                    'name' => 'DishImage[image_id]',
                    'sessionParam' => 'SESSION_ID',
                    'options' => array(
                        'fileObjName' => 'DishImage[image_id]',
                        'fileExt' => '*.jpg;*.jpeg;*.png;*.gif',
                        'uploader' => $this->createUrl('dishImage/upload'),
                        'auto' => false,
                        'width' => 215,
                        'height' => 45,
                        'multi' => true,
                        'buttonText' => Yii::t('backend', 'Select images'),
                        'formData' => array(
                            'DishImage[dish_id]' => $model->id,
                            'DishImage[sort]' => 500,
                            'DishImage[status]' => 1,
                        ),
                        'onQueueComplete' => 'js:function(queueData) {
                                                    window.location.reload(true);
                                                }',
                        'buttonClass' => 'btn btn-primary'
                    )
                )); ?>
                <?php echo CHtml::submitButton(Yii::t('backend', 'Upload'), array(
                    'class' => 'btn btnUpload',
                    'name' => 'upload',
                )); ?>
            </div>
        </div>
        <?php echo CHtml::hiddenField('DishImage[dish_id]', $model->id); ?>
    </form>
    <div class="clearfix"></div>
</div>

<?php if($model->dishImages) { ?>
    <?php $form = $this->beginWidget('backend.components.ActiveForm', array(
        'model' => $model,
        'overrideType' => 'vertical',
        'action' => array('dishImage/sort'),
        'fieldsetLegend' => Yii::t('backend', 'Preview & sort'),
        'enableAjaxValidation' => false,
        'formActions' => array(
            'apply' => CHtml::submitButton(Yii::t('backend', 'Save sorting'), array(
                'class' => 'btn',
                'name' => 'apply',
                'onclick' => 'js:$("#thumbnails-order").val($("ul#thumbnails").sortable("serialize"))'
            ))
        )
    )); ?>

        <ul id="thumbnails" class="thumbnails">
            <?php foreach($model->dishImages as $item) { /** @var $item Dish */?>
                <li class="span1" id="img-<?php echo $item->id; ?>">
                    <div class="thumbnail">
                        <?php echo File::htmlImage($item->image, '', array('width' => 100)); ?>
                        <div class="actions">
                            <a href="<?php echo $this->createUrl('dishImage/update', array('id' => $item->id)); ?>"
                               title="<?php echo Yii::t('backend', 'Edit'); ?>">

                                <img alt="<?php echo Yii::t('backend', 'Edit'); ?>" src="/backend/img/update.png">
                            </a>
                            <?php echo CHtml::link('<img alt="'.Yii::t('backend', 'Edit').'" src="/backend/img/delete.png">',
                                array('dishImage/delete', 'id' => $item->id, 'ajax' => 1),
                                array('class' => 'deleteImage')
                            ); ?>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <input id="thumbnails-order" name="DishImage[order]" type="hidden" value="" />
        <?php echo CHtml::hiddenField('DishImage[dish_id]', $model->id); ?>

    <?php $this->endWidget(); ?>
<?php } ?>

<?php
cs()->registerScript('upload-button', <<<JS
    $('.btnUpload').on('click', function(e) {
        e.preventDefault();
        $('#{$uploadifyId}').uploadify("upload","*");
        return false;
    });
JS
);

cs()->registerScript('delete-image', "
jQuery('.thumbnails a.deleteImage').on('click', function(e) {
    e.preventDefault();
    if(!confirm('".Yii::t('backend', 'Are you sure?')."'))
        return false;

    var el = this;
    $.ajax({
        type: 'post',
        url: $(this).attr('href'),
        data: {'".Yii::app()->getRequest()->csrfTokenName."': '".Yii::app()->getRequest()->getCsrfToken()."'},
        success: function(data) {
            var \$ul = $(el).closest('ul');
            if($('li', \$ul).length > 1)
                $(el).closest('li').remove();
            else
                \$ul.remove();
        },
        error:function(XHR) {
            alert(XHR);
        }
	});

	return false;
});
");

cs()->registerCoreScript('jquery.ui');
cs()->registerScript('jui-sortable', '$("#thumbnails").sortable();');
?>