<?php
/** @var $this OptionController */
/** @var $model Option */
?>
<?php
$this->pageTitle = Yii::t('cp', 'Clone "{title}"', array('{title}' => $model->title));
$this->breadcrumbs = array(
    Yii::t('cp', 'Options') => array('admin'),
    Yii::t('cp', 'Clone'),
);

$this->menu = array(
    array('label' => Yii::t('cp', 'Manage option'), 'url' => array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle
)); ?>