<?php
/** @var $this CategoryController */
/** @var $model Category */
/** @var $models Category[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Create');
$this->breadcrumbs = array(
	Yii::t('backend', 'Categories') => array('admin'),
	Yii::t('backend', 'Create'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
