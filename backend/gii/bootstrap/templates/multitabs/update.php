<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
/** @var $this CrudCode */
?>
<?php echo '<?php
/** @var $this '.$this->getControllerClass().' */
/** @var $model '.$this->getModelClass().' */
/** @var $models '.$this->getModelClass().'[] */
?>'."\n"; ?>
<?php
echo "<?php\n";
echo "\$this->pageTitle = Yii::t('backend', 'Update \"{title}\"', array('{title}' => \$model->getDisplayTitle()));\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
	Yii::t('backend', '$label') => array('admin'),
	Yii::t('backend', 'Update'),
);\n";
?>
?>

<?php echo "<?php"; ?> echo $this->renderPartial('_form', array(
    'model' => $model,
<?php if(new $this->model instanceof LangActiveRecord) { ?>
    'models' => $models,
    'languages' => $languages,
<?php } ?>
    'legend' => $this->pageTitle,
)); <?php echo "?>\n"; ?>