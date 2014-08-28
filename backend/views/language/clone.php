<?php
/** @var $this LanguageController */
/** @var $model Language */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Clone "{title}"', array('{title}' => $model->title));
$this->breadcrumbs = array(
	Yii::t('backend', 'Languages') => array('admin'),
	Yii::t('backend', 'Clone'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Manage language'), 'url' => array('admin')),
); ?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
