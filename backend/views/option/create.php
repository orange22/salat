<?php
/** @var $this OptionController */
/** @var $model Option */
?>
<?php
$this->pageTitle = Yii::t('cp', 'Create');
$this->breadcrumbs = array(
    Yii::t('cp', 'Options') => array('admin'),
    Yii::t('cp', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('cp', 'Manage option'), 'url' => array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle
)); ?>