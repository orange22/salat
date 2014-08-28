<?php
/** @var $this GalleryItemController */
/** @var $model GalleryItem */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Update "{title}"', array('{title}' => $model->id));
$this->breadcrumbs = array(
	Yii::t('backend', 'Update Image'),
);

$this->menu = array(); ?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
