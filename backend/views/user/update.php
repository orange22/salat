<?php
/** @var $this UserController */
/** @var $model User */
/** @var $authItemModel AuthItem */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Update "{title}"', array('{title}' => $model->display_name));
$this->breadcrumbs = array(
	Yii::t('backend', 'Users') => array('admin'),
	Yii::t('backend', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Create user'), 'url' => array('create')),
    array('label' => Yii::t('backend', 'Manage user'), 'url' => array('admin')),
); ?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'authItemModel' => $authItemModel,
    'legend' => $this->pageTitle,
)); ?>
