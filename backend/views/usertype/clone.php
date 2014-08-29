<?php
/** @var $this UsertypeController */
/** @var $model Usertype */
/** @var $models Usertype[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Clone "{title}"', array('{title}' => $model->getDisplayTitle()));
$this->breadcrumbs = array(
	Yii::t('backend', 'Usertypes') => array('admin'),
	Yii::t('backend', 'Clone'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
