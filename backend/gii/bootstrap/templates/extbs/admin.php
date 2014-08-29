<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
/** @var $this BootstrapCode */
?>
<?php echo '<?php
/** @var $this '.$this->getControllerClass().' */
/** @var $model '.$this->getModelClass().' */
/** @var $form CActiveForm */
?>'."\n"; ?>
<?php
echo "<?php\n";
echo "\$this->pageTitle = Yii::t('backend', 'Manage');\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
	Yii::t('backend', '$label') => array('admin'),
	Yii::t('backend', 'Manage'),
);\n";
?>
?>

<h3><?php echo "<?php"; ?> echo $this->pageTitle; <?php echo "?>"; ?></h3>

<?php echo "<?php"; ?> $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); <?php echo "?>\n"; ?>

    <?php echo "<?php"; ?> $this->widget('backend.components.AdminView', array(
        'model' => $model,
        'columns' => array(
<?php foreach($this->tableSchema->columns as $column) {
    if($column->name === 'id' && array_key_exists('pid', $this->tableSchema->columns)) continue;
?>
<?php 
if($column->name == 'image_id')
continue;
if($column->name !== 'language_id' && (($pos = strpos($column->name, '_id')) || ($pos = strpos($column->name, '_pid')))) {
    $rel = substr($column->name, 0, $pos);
?>
            array(
                'name' => '<?php echo $column->name; ?>',
                'value' => '$data-><?php echo $rel; ?> ? $data-><?php echo $rel; ?>->title : null',
                'filter' => CHtml::listData(<?php echo ucfirst($rel) ?>::model()->findAll(), '<?php echo substr($column->name, $pos + 1); ?>', 'title'),
            ),
<?php } else { ?>
            '<?php echo $column->name; ?>',
<?php } // if ?>
<?php } ?>
        ),
    )); ?>

<?php echo "<?php"; ?> $this->endWidget(); <?php echo "?>"; ?>