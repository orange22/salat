<?php /** @var $this BootstrapCode */ ?>
<?php echo '<?php
/** @var $this '.$this->getControllerClass().' */
/** @var $model '.$this->getModelClass().' */
/** @var $models '.$this->getModelClass().'[] */
/** @var $languages array */
?>
'; ?>
<?php echo '<ul id="form" class="nav nav-pills">
    <li class="active"><a href="#form-description" data-toggle="pill"><?php echo Yii::t(\'backend\', \'Description\'); ?></a></li>
    <?php if($model->getIsNewRecord()) { ?>
        <li class="disabled">
            <a href="#" title="<?php echo Yii::t(\'backend\', \'Save page first\'); ?>"
               rel="tooltip"><?php echo Yii::t(\'backend\', \'Upload\'); ?></a>
        </li>
        <li class="disabled">
            <a href="#" title="<?php echo Yii::t(\'backend\', \'Save page first\'); ?>"
               rel="tooltip"><?php echo Yii::t(\'backend\', \'Meta tags\'); ?></a>
        </li>
    <?php } else { ?>
        <li><a href="#form-upload" data-toggle="pill"><?php echo Yii::t(\'backend\', \'Upload\'); ?></a></li>
        <li><a href="#form-seo" data-toggle="pill"><?php echo Yii::t(\'backend\', \'Meta tags\'); ?></a></li>
    <?php } ?>
</ul>
'; ?>
<?php echo '<div class="tab-content">
    <div id="form-description" class="tab-pane fade in active">
        <?php $this->renderPartial(\'_form_description\', array(
            \'model\' => $model,
            \'legend\' => $legend,
        )); ?>
    </div>
    <div id="form-upload" class="tab-pane fade">
        <?php if(!$model->getIsNewRecord()) { ?>
            <?php $this->renderPartial(\'_form_upload\', array(
                \'model\' => $model,
            )); ?>
        <?php } ?>
    </div>
    <div id="form-seo" class="tab-pane fade">
        <?php if(!$model->getIsNewRecord()) { ?>
            <?php $this->renderPartial(\'//inc/_form_seo\', array(
                \'model\' => $model,
            )); ?>
        <?php } ?>
    </div>
</div>'; ?>