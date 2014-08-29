<?php
/** @var $this PartnerController */
/** @var $model Partner */
/** @var $models Partner[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Create');
$this->breadcrumbs = array(
	Yii::t('backend', 'Partners') => array('admin'),
	Yii::t('backend', 'Create'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
