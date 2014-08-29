<?php
/** @var $this ActionController */
/** @var $model Action */
/** @var $models Action[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Create');
$this->breadcrumbs = array(
	Yii::t('backend', 'Actions') => array('admin'),
	Yii::t('backend', 'Create'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
