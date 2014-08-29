<?php /** @var $this BootstrapCode */ ?>
<?php echo '<?php
/** @var $this '.$this->getControllerClass().' */
/** @var $model '.$this->getModelClass().' */
/** @var $form ActiveForm */
/** @var $language string */
?>
'; ?>

<?php
$model = new $this->modelClass;
foreach($this->tableSchema->columns as $column)
{
    if($column->autoIncrement || in_array($column->name, array('language_id', 'pid')))
        continue;
    if($model instanceof LangActiveRecord && in_array($column->name, $model->fixedAttributes()))
        continue;
?>
<?php echo "<?php echo ".$this->generateLangActiveRow($this->modelClass, $column)."; ?>\n"; ?>
<?php } ?>
<?php echo '<?php echo $form->hiddenField($model, "[{$language}]language_id", array(\'value\' => $language)); ?>'; ?>