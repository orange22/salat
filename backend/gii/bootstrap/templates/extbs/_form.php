<?php /** @var $this BootstrapCode */ ?>
<?php echo '<?php
/** @var $this '.$this->getControllerClass().' */
/** @var $model '.$this->getModelClass().' */
/** @var $models '.$this->getModelClass().'[] */
/** @var $form ActiveForm */
?>
'; ?>

<?php
echo <<<OUT
<?php \$form = \$this->beginWidget('backend.components.ActiveForm', array(
    'model' => \$model,
    'fieldsetLegend' => \$legend,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:formAfterValidate',
    ),
)); ?>

OUT;
?>

<?php
$model = new $this->modelClass;
foreach($this->tableSchema->columns as $column)
{
    /** @var $column CMysqlColumnSchema */
    if($column->autoIncrement || in_array($column->name, array('language_id', 'pid')))
        continue;
    if($model instanceof LangActiveRecord && !in_array($column->name, $model->fixedAttributes()))
        continue;
    ?>
    <?php echo "<?php echo ".$this->generateActiveRow($this->modelClass, $column)."; ?>\n"; ?>
<?php } ?>
<?php if(array_key_exists('language_id', $this->tableSchema->columns)) {
    echo "
    <?php \$this->renderPartial('//inc/_form_tabs', array(
        'models' => \$models,
        'languages' => \$languages,
        'tplVars' => array(
            'form' => \$form,
        ),
    )); ?>

    <?php echo \$form->hiddenField(\$model, 'pid'); ?>
    <hr />
    ";
} ?>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>