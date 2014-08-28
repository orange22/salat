<?php $this->breadcrumbs = array(
    Rights::t('core', 'Assignments') => array('assignment/view'),
    $model->getName(),
); ?>

<div id="userAssignments" class="row-fluid">

    <div class="span8">

        <h3><?php echo Rights::t('core', 'Assignments for :username', array(
            ':username' => $model->getName()
        )); ?></h3>

        <?php $this->widget('TbGridView', array(
        'dataProvider' => $dataProvider,
        'template' => '{items}',
        'hideHeader' => true,
        'emptyText' => Rights::t('core', 'This user has not been assigned any items.'),
        'htmlOptions' => array('class' => 'grid-view user-assignment-table mini'),
        'columns' => array(
            array(
                'name' => 'name',
                'header' => Rights::t('core', 'Name'),
                'type' => 'raw',
                'htmlOptions' => array('class' => 'name-column'),
                'value' => '$data->getNameText()',
            ),
            array(
                'name' => 'type',
                'header' => Rights::t('core', 'Type'),
                'type' => 'raw',
                'htmlOptions' => array('class' => 'type-column'),
                'value' => '$data->getTypeText()',
            ),
            array(
                'header' => '&nbsp;',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'actions-column'),
                'value' => '$data->getRevokeAssignmentLink()',
            ),
        )
    )); ?>

    </div>

    <div class="span4">

        <h3><?php echo Rights::t('core', 'Assign item'); ?></h3>

        <?php if($formModel !== null): ?>

        <div class="form">

            <?php $this->renderPartial('_form', array(
            'model' => $formModel,
            'itemnameSelectOptions' => $assignSelectOptions,
        )); ?>

        </div>

        <?php else: ?>

			<p class="info"><?php echo Rights::t('core', 'No assignments available to be assigned to this user.'); ?>

        <?php endif; ?>

    </div>

</div>
