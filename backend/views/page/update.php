<?php
/** @var $this PageController */
/** @var $model Page */
/** @var $models Page[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Update "{title}"', array('{title}' => $model->getDisplayTitle()));
$this->breadcrumbs = array(
	Yii::t('backend', 'Pages') => array('admin'),
	Yii::t('backend', 'Update'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
