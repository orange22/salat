<?php
/** @var $this DishImageController */
/** @var $model DishImage */
/** @var $models DishImage[] */
?>
<?php
//$this->pageTitle = Yii::t('backend', 'Update "{file}"', array('{file}' => $model->getDisplayTitle()));
$this->pageTitle='';
$this->breadcrumbs = array(
	Yii::t('backend', 'Dish Images') => array('admin'),
	Yii::t('backend', 'Update'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
