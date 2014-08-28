<?php
/** @var $this LanguageController */
/** @var $model Language */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Update "{title}"', array('{title}' => $model->title));
$this->breadcrumbs = array(
	Yii::t('backend', 'Languages') => array('admin'),
	Yii::t('backend', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Create language'), 'url' => array('create')),
    array('label' => Yii::t('backend', 'Manage language'), 'url' => array('admin')),
); ?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
