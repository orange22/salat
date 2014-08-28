<?php $this->breadcrumbs = array(
    Rights::t('core', 'Create :type', array(':type' => Rights::getAuthItemTypeName($_GET['type']))),
); ?>

<div class="createAuthItem">

    <?php $this->renderPartial('_form', array(
    'model' => $formModel,
    'legend' => Rights::t('core', 'Create :type', array(
        ':type' => Rights::getAuthItemTypeName($_GET['type']),
    ))
)); ?>

</div>