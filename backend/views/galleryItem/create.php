<?php
/** @var $this GalleryItemController */
/** @var $model GalleryItem */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Create');
$this->breadcrumbs = array(
	Yii::t('backend', 'Gallery Images') => array('admin'),
	Yii::t('backend', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Manage gallery image'), 'url' => array('admin')),
); ?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
