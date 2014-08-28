<?php
/** @var $this FileController */
/** @var $model File */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('backend', 'Files') => array('admin'),
    Yii::t('backend', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Manage file'), 'url' => array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'models' => $models,
    'languages' => $languages,
    'legend' => Yii::t('backend', 'Update file "{title}"', array(
        '{title}' => $model->file,
    )),
)); ?>
