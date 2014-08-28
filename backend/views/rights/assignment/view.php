<?php $this->breadcrumbs = array(
    Rights::t('core', 'Assignments'),
); ?>

<div id="assignments">

    <h3><?php echo Rights::t('core', 'Assignments'); ?></h3>

    <p>
        <?php echo Rights::t('core', 'Here you can view which permissions has been assigned to each user.'); ?>
    </p>

    <?php $this->widget('TbGridView', array(
    'dataProvider' => $dataProvider,
    'template' => "{items}\n{pager}",
    'emptyText' => Rights::t('core', 'No users found.'),
    'htmlOptions' => array('class' => 'grid-view assignment-table'),
    'columns' => array(
        array(
            'name' => 'name',
            'header' => Rights::t('core', 'Name'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'name-column'),
            'value' => '$data->getName() ? $data->getAssignmentNameLink() : "&mdash;"',
        ),
        array(
            'name' => 'assignments',
            'header' => Rights::t('core', 'Roles'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'role-column'),
            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
        ),
        array(
            'name' => 'assignments',
            'header' => Rights::t('core', 'Tasks'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'task-column'),
            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
        ),
        array(
            'name' => 'assignments',
            'header' => Rights::t('core', 'Operations'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'operation-column'),
            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
        ),
    )
)); ?>

</div>