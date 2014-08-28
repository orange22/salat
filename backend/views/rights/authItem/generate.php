<?php $this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Generate items'),
); ?>

<div id="generator">

    <h3><?php echo Rights::t('core', 'Generate items'); ?></h3>

    <p><?php echo Rights::t('core', 'Please select which items you wish to generate.'); ?></p>

    <div class="form">
        <?php $form = $this->beginWidget('backend.components.ActiveForm', array(
        'formActions' => false
    )); ?>

        <table class="table table-bordered table-striped table-condensed grid-view generate-item-table" border="0"
               cellpadding="0" cellspacing="0">

            <tbody>

            <tr class="application-heading-row">
                <th colspan="3"><?php echo Rights::t('core', 'Application'); ?></th>
            </tr>

            <?php $this->renderPartial('_generateItems', array(
                'model' => $model,
                'form' => $form,
                'items' => $items,
                'existingItems' => $existingItems,
                'displayModuleHeadingRow' => true,
                'basePathLength' => strlen(Yii::app()->basePath),
            )); ?>

            </tbody>

        </table>

        <div class="form-actions">

            <div class="btn-toolbar">

                <div class="btn-group">
                    <?php echo CHtml::link(Rights::t('core', 'Select all'), '#', array(
                    'onclick' => "jQuery('.generate-item-table').find(':checkbox').attr('checked', 'checked'); return false;",
                    'class' => 'btn'
                )); ?>
                    <?php echo CHtml::link(Rights::t('core', 'Select none'), '#', array(
                    'onclick' => "jQuery('.generate-item-table').find(':checkbox').removeAttr('checked'); return false;",
                    'class' => 'btn'
                )); ?>
                </div>

                <div class="btn-group">
                    <?php echo CHtml::submitButton(Rights::t('core', 'Generate'), array(
                    'class' => 'btn btn-primary',
                )); ?>
                </div>

            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>