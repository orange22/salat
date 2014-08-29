<?php
/** @var $this OrderController */
/** @var $model Order */
/** @var $models Order[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Clone "{title}"', array('{title}' => $model->getDisplayTitle()));
$this->breadcrumbs = array(
	Yii::t('backend', 'Orders') => array('admin'),
	Yii::t('backend', 'Clone'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
