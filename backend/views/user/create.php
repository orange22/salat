<?php
/** @var $this UserController */
/** @var $model User */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Create');
$this->breadcrumbs = array(
	Yii::t('backend', 'Users') => array('admin'),
	Yii::t('backend', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Manage user'), 'url' => array('admin')),
); ?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'authItemModel' => $authItemModel,
    'legend' => $this->pageTitle,
)); ?>
