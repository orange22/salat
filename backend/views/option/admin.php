<?php
/** @var $this OptionController */
/** @var $model Option */
/** @var $form TbActiveForm */
?>
<?php
$this->pageTitle = Yii::t('cp', 'Manage');
$this->breadcrumbs = array(
    Yii::t('cp', 'Options') => array('admin'),
    Yii::t('cp', 'Manage'),
);

$this->menu = array(
    array('label' => Yii::t('cp', 'Create option'), 'url' => array('create')),
);
?>

<h3><?php echo $this->pageTitle; ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>

<?php $this->widget('backend.components.AdminView', array(
    'model' => $model,
    'actionButtons' => array('create'),
    'buttonColumn' => array(
        'buttons' => array(
            'clone' => array(
                'visible' => 'Yii::app()->user->checkAccess("Option.Create")',
            ),
            'delete' => array(
                'visible' => 'Yii::app()->user->checkAccess("Option.Delete")',
            ),
        )
    ),
    'columns' => array(
        array(
            'name' => 'key',
            'visible' => ($permCreate = Yii::app()->user->checkAccess('Option.Create')),
        ),
        'title',
        array(
            'name' => 'value',
            'value' => '$data->i18n
                ? (isset($data->value[Yii::app()->language]) ? $data->value[Yii::app()->language] : "")
                : $data->value'
        ),
        array(
            'name' => 'group',
            'value' => 'Yii::t("cp", $data->group)'
        ),
        array(
            'name' => 'role',
            'filter' => User::getRoleList(),
            'visible' => $permCreate,
        ),
        array(
            'name' => 'type',
            'filter' => Option::getTypes(),
            'value' => 'Option::getTypes($data->type)',
            'visible' => $permCreate,
        ),
        array(
            'name' => 'serialized',
            'filter' => array(0 => Yii::t('cp', 'No'), 1 => Yii::t('cp', 'Yes')),
            'visible' => $permCreate,
        ),
        array(
            'name' => 'i18n',
            'filter' => array(0 => Yii::t('cp', 'No'), 1 => Yii::t('cp', 'Yes')),
            'visible' => $permCreate,
        ),
        'sort'
    )
)); ?>

<?php $this->endWidget(); ?>