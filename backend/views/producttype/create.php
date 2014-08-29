<?php
/** @var $this DishtypeController */
/** @var $model Dishtype */
/** @var $models Dishtype[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Create');
$this->breadcrumbs = array(
	Yii::t('backend', 'Dishtypes') => array('admin'),
	Yii::t('backend', 'Create'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
