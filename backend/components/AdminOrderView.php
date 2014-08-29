<?php
/**
 * AdminView
 */
class AdminOrderView extends TbGridView2
{
    /**
     * Grid action buttons
     *
     * @var array
     */
    public $actionButtons = array('create', 'status', 'delete');

    /**
     * Button column options
     *
     * @var array
     */
    public $buttonColumn = array();

    /**
     * Model
     *
     * @var CActiveRecord
     */
    public $model = null;

    /**
     * Field using for multiple rows manipulating
     *
     * @var string
     */
    public $keyField = '';

    public function init()
    {
        if(!$this->model)
            throw new CException(Yii::t('backend', 'The "model" property cannot be empty.'));

        $this->id = $this->controller->getId().'-grid';
        $this->type = 'bordered striped condensed';
        $this->template = '{actions}'.$this->template;
        $this->selectableRows = 2;
        if(!$this->dataProvider)
        {
            $this->dataProvider = $this->model->search();
        }
        if(!$this->filter)
        {
            $this->filter = $this->model;
        }
        if(!$this->keyField)
        {
            $this->keyField = $this->model->hasAttribute('pid') ? 'pid' : 'id';
        }
        $this->prepareColumns();
        
        parent::init();
    }

    protected function initColumns()
    {
        if(!($this->dataProvider instanceof CActiveDataProvider))
        {
            foreach($this->columns as $i => $column)
            {
                if(is_string($column))
                {
                    $this->columns[$i] = array(
                        'name' => $column,
                        'header' => $this->model->getAttributeLabel($column)
                    );

                    continue;
                }

                if(!isset($column['name']) || isset($column['header']))
                {
                    continue;
                }

                $this->columns[$i]['header'] = $this->model->getAttributeLabel($column['name']);
            }
        }

        parent::initColumns();
    }

    /**
     * Prepare CGridView columns
     */
    protected function prepareColumns()
    {
        $columns = array_merge(array(
            CMap::mergeArray(array(
                'class' => 'CButtonColumn',
                'template' => '{update} {clone} {delete}',
                'header' => CHtml::dropDownList(
                    'pageSize',
                    Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
                    array(10 => 10, 20 => 20, 50 => 50, 100 => 100, 500 => 500),
                    array(
                        'onchange' => "$.fn.yiiGridView.update('".$this->id."', {data: {pageSize: $(this).val()}})",
                    )),
                'buttons' => array(
                    'update' => array(
                        'url' => "Yii::app()->getController()->createUrl('update', array('{$this->keyField}' => \$data->{$this->keyField}))",
                    ),
                    'clone' => array(
                        'label' => Yii::t('backend', 'Clone'),
                        'url' => "Yii::app()->getController()->createUrl('clone', array('{$this->keyField}' => \$data->{$this->keyField}))",
                        'imageUrl' => '/backend/img/clone.png',
                        'options' => array(),
                        'visible' => 'true',
                    ),
                    'delete' => array(
                        'url' => "Yii::app()->getController()->createUrl('delete', array('{$this->keyField}' => \$data->{$this->keyField}))",
                    ),
                )
            ), $this->buttonColumn),

            array(
                'class' => 'CCheckBoxColumn',
                'name' => $this->keyField,
                'checkBoxHtmlOptions' => array(
                    'name' => $this->keyField.'[]'
                ),
            ),
        ), $this->columns);

        if($pos = array_search('status', $columns))
        {
            $columns[$pos] = array(
                'name' => 'status',
                'value' => '$data->status ? Yii::t("backend", "Enabled") : Yii::t("backend", "Disabled")',
                'filter' => array(0 => Yii::t('backend', 'Disabled'), 1 => Yii::t('backend', 'Enabled')),
            );
        }
		if($pos = array_search('main', $columns))
        {
            $columns[$pos] = array(
                'name' => 'main',
                'value' => '$data->main ? Yii::t("backend", "Enabled") : Yii::t("backend", "Disabled")',
                'filter' => array(0 => Yii::t('backend', 'Disabled'), 1 => Yii::t('backend', 'Enabled')),
            );
        }
       /*
        if($pos = array_search('language_id', $columns))
                {
                    $langList = Language::getList();
                    $columns[$pos] = array(
                        'name' => 'language_id',
                        'filter' => $langList,
                        'visible' => count($langList) > 1
                    );
                }*/
        

 		if($pos = array_search('date_create', $columns))
        {
            $columns[$pos] = array(
                'name' => 'date_create',
                'filter' => array(1,2),
            );
        }

        $this->columns = $columns;
    }

    public function registerClientScript()
    {
        parent::registerClientScript();

        if(empty($this->actionButtons))
        {
            return;
        }

        $o = "
            $('body').on('click', '.btnBulk', function(e) {
                if($('input[name=\"{$this->keyField}[]\"]:checked').length < 1) {
                    e.stopImmediatePropagation();

                    return false;
                }

                return true;
            });
        ";

        Yii::app()->clientScript->registerScript('bulkActions', $o);
    }

    /**
     * Grid view action buttons
     */
    protected function renderActions()
    {
        if(empty($this->actionButtons))
            return;

        $ctrl = $this->getController();
        $perm = ucfirst(get_class($this->model)).'.';
        $accessCtrl = Yii::app()->user->checkAccess($perm.'*');
        echo CHtml::openTag('div', array('class' => 'grid-actions'));

        if(in_array('create', $this->actionButtons) && ($accessCtrl || Yii::app()->user->checkAccess($perm.'Create')))
        {
            echo CHtml::link(Yii::t('backend', 'Create'), array('create'), array(
                'class' => 'btn btnCreate'
            ));
        }

        if(in_array('status', $this->actionButtons) && ($accessCtrl || Yii::app()->user->checkAccess($perm.'Update')))
        {
            echo CHtml::linkButton(Yii::t('backend', 'Enable'), array(
                'class' => 'btn btnBulk btnEnable',
                'submit' => $ctrl->createUrl('bulkEnable'),
            ));
            echo CHtml::linkButton(Yii::t('backend', 'Disable'), array(
                'class' => 'btn btnBulk btnDisable',
                'submit' => $ctrl->createUrl('bulkDisable'),
            ));
        }

        if(!empty($this->actionButtons))
        {
            foreach($this->actionButtons as $aButt)
            {
                if(in_array($aButt, array('create', 'status', 'delete')))
                {
                    continue;
                }

                echo $aButt;
            }
        }

        if(in_array('delete', $this->actionButtons) && ($accessCtrl || Yii::app()->user->checkAccess($perm.'Delete')))
        {
            echo CHtml::linkButton(Yii::t('backend', 'Delete'), array(
                'class' => 'btn btn-danger btnBulk btnDelete',
                'confirm' => Yii::t('backend', 'Are you sure?'),
                'submit' => $ctrl->createUrl('bulkDelete'),
            ));
        }

        echo CHtml::closeTag('div');
    }
}