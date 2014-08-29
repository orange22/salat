<?php
/** @var $this SubscriberController */
/** @var $model Subscriber */
/** @var $models Subscriber[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Update "{title}"', array('{title}' => $model->getDisplayTitle()));
$this->breadcrumbs = array(
	Yii::t('backend', 'Subscribers') => array('admin'),
	Yii::t('backend', 'Update'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
