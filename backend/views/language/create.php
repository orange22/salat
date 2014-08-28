<?php
/** @var $this LanguageController */
/** @var $model Language */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Create');
$this->breadcrumbs = array(
	Yii::t('backend', 'Languages') => array('admin'),
	Yii::t('backend', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Manage language'), 'url' => array('admin')),
); ?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
