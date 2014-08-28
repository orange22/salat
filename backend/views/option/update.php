<?php
/** @var $this OptionController */
/** @var $model Option */
/** @var $languages array */
?>
<?php
$this->pageTitle = Yii::t('cp', 'Update "{title}"', array('{title}' => $model->title));
$this->breadcrumbs = array(
    Yii::t('cp', 'Options') => array('admin'),
    Yii::t('cp', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('cp', 'Create option'), 'url' => array('create')),
    array('label' => Yii::t('cp', 'Manage option'), 'url' => array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle
)); ?>