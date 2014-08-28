<?php $this->beginContent(Rights::module()->appLayout); ?>
<div class="row-fluid">
    <div class="span12">

        <?php if($this->id !== 'install'): ?>
        <?php $this->renderPartial('/_menu'); ?>
        <?php endif; ?>

        <?php if(isset($this->breadcrumbs)): ?>
            <?php $this->widget('TbBreadcrumbs', array(
                'links' => $this->breadcrumbs,
                'separator' => '/',
                'homeLink' => CHtml::link(Yii::t('backend', 'Home'), Yii::app()->homeUrl),
            )); ?><!-- breadcrumbs -->
        <?php endif ?>

        <?php $this->renderPartial('/_flash'); ?>
        <?php echo $content; ?>

    </div>
</div>
<?php $this->endContent(); ?>