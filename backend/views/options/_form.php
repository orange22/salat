<?php
/** @var $this OptionsController */
/** @var $model OptionsForm */
/** @var $options Option[] */
?>
<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
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
                            echo CHtml::dropDownList("Options[{$option->key}]", $option->value, (array)$option->cfg(null, 'option'));
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

            <?php } else { ?>

                <label class="control-label" for="<?php echo CHtml::getIdByName("Options[{$option->key}][".key($languages)."]"); ?>">
                  <?php echo $option->title; ?>
                </label>

                <div class="controls">
                    <?php
                    $tabs = array();
                    $active = true;
                    foreach($languages as $lang => $title)
                    {
                        switch($option->type)
                        {
                            case 'dropDown':
                                echo CHtml::dropDownList("Options[{$option->key}][{$lang}]", $option->value[$lang], $option->cfg(null, 'option'));
                            break;
                            case 'textArea':
                                $input = CHtml::textArea("Options[{$option->key}][{$lang}]", $option->value[$lang], array('rows' => 10, 'cols' => 50, 'class' => 'span5'));
                            break;
                            case 'textField':
                            default:
                                $input = CHtml::textField("Options[{$option->key}][{$lang}]", $option->value[$lang], array('size' => 32, 'class' => 'span5'));
                        }
                        $tabs[$title] = array(
                            'id' => 'tab-'.$option->id.'-'.$lang,
                            'label' => $title,
                            'active' => $active,
                            'content' => $input,
                        );
                        $active = false;
                    }

                    $this->widget('TbTabs', array(
                        'type' => 'tabs',
                        'tabs' => $tabs
                    )); ?>
                </div>

            <?php } // if i18n ?>

        </div>
    <?php } // foreach ?>

<?php $this->endWidget(); ?>