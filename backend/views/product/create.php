<?php
/** @var $this ProductController */
/** @var $model Product */
/** @var $models Product[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Create');
$this->breadcrumbs = array(
	Yii::t('backend', 'Products') => array('admin'),
	Yii::t('backend', 'Create'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
