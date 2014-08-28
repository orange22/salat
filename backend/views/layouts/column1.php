<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
    <div class="row-fluid">

        <?php if(!Yii::app()->user->isGuest) { ?>
            <?php if(isset($this->breadcrumbs)) { ?>
                <?php $this->widget('TbBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                    'separator' => '/',
                    'homeLink' => CHtml::link(Yii::t('backend', 'Home'), Yii::app()->homeUrl),
                )); ?><!-- breadcrumbs -->
            <?php } ?>
        <?php } ?>

        <?php if(Yii::app()->user->getFlashes(false)) { ?>
            <?php $this->widget('TbAlert', array(
                'id' => 'alert',
                'htmlOptions' => array(
                    'class' => ''
                ),
            )); ?>
        <?php } ?>

        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>