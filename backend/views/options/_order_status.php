<?php
/** @var $this OptionsController */
/** @var $model OptionsForm */
/** @var $options Option[] */
?>
<?php $form = $this->beginWidget('app.components.cp.ActiveForm', array(
    'fieldsetLegend' => $legend,
    'enableAjaxValidation' => false,
)); ?>

    <?php foreach($options as $option) { ?>
        <div class="control-group ">

            <?php if(!$option->i18n) { ?>

                <label class="control-label" for="<?php echo CHtml::getIdByName("Options[{$option->key}]"); ?>">
                    <?php echo $option->title; ?>
                </label>

                <div class="controls">
                    <?php
                    switch($option->type)
                    {
                        case 'dropDown':
                            echo CHtml::dropDownList("Options[{$option->key}]", $option->value, $statuses);
                        break;
                        case 'textArea':
                            echo CHtml::textArea("Options[{$option->key}]", $option->value, array('rows' => 10, 'cols' => 50, 'class' => 'span5'));
                        break;
                        case 'textField':
                        default:
                            echo CHtml::textField("Options[{$option->key}]", $option->value, array('size' => 32, 'class' => 'span5'));
                    } ?>
                    <?php if($option->hint) { ?>
                        <p class="help-block"><?php echo $option->hint; ?></p>
                    <?php } ?>
                </div>

            <?php } ?>

        </div>
    <?php } // foreach ?>

<?php $this->endWidget(); ?>