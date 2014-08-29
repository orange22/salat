<?php
/**
 * BackController is the customized base controller class for back end.
 * All back end controller classes for this application should extend from this base class.
 */
class BackController extends RController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * Model class name
     *
     * @var string
     */
    protected $modelName = '';

    /**
     * Basic view paths
     *
     * @var array
     */
    protected $view = array(
        'admin' => 'admin',
        'clone' => 'clone',
        'create' => 'create',
        'update' => 'update',
    );

	public function tinymce($name, $templates = false)
    {
        $args = array(
            'name' => $name,
            'editorTemplate' => 'full',
            'useSwitch' => false,
            'options' => array(
                'theme_advanced_buttons1' => "newdocument,print,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,removeformat,cleanup,|,visualaid,visualchars,|,ltr,rtl,|,code,fullscreen,preview,|,help",
                'theme_advanced_buttons2' => "formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,|,bold,italic,underline,strikethrough,|,sub,sup",
                'theme_advanced_buttons3' => "justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,hr,advhr,nonbreaking,pagebreak,blockquote,|,charmap,emotions,media,image,|,link,unlink,anchor,|,insertdate,inserttime,|,template",
                'theme_advanced_buttons4' => "",
                'spellchecker_languages' => "",
                'width' => '70%',
                'height' => '300px',
            ),
        );

        if($templates)
        {
            $args['contentCSS'] = '/backend/css/tinymce.css';
            $args['options']['template_external_list_url'] = '/backend/js/templates.js?'.(YII_DEBUG ? time() : date('YmdHi'));
            $args['plugins'] = array('trailing');
        }

        return $this->widget('backend.extensions.tinymce.ETinyMce', $args, true);
    }

    public function init()
    {
        parent::init();
        $this->defaultAction = 'admin';
        $this->setModelName(ucfirst($this->getId()));
    }

    /**
     * Get controller title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getModelName();
    }

    /**
     * Create return url
     *
     * @param array $params
     * @return string
     */
    public function returnUrl($params = array())
    {
        foreach(array('id', 'pid') as $arg)
        {
            if(!array_key_exists($arg, $params) && isset($_GET[$arg]))
                $params[$arg] = $_GET[$arg];
        }

        return base64_encode($this->createUrl('', $params, '&'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        /** @var $model BaseActiveRecord */
        $model = $this->getNewModel('search');
        $model->unsetAttributes(); // clear any default values

        if(isset($_GET[$this->getModelName()]))
            $model->attributes = $_GET[$this->getModelName()];

        $model->restoreGridState();

        $this->render($this->view['admin'], array(
            'model' => $model,
        ));
    }

    /**
     * Clone model
     *
     * @param int $id Source model
     * @return void
     */
    public function actionClone($id)
    {
        /** @var $model BaseActiveRecord */
        $model = clone $this->loadModel($id);
        $model->setScenario('create');

        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['clone'], array(
                'model' => $model,
            )
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        /** @var $model BaseActiveRecord */
        $model = $this->getNewModel();

        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['create'], array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     *
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
     * @return void
     */
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $this->beforeDelete($id);
            $this->loadModel($id)->delete();
            $this->afterDelete($id);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
            {
                $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
            }
        }
        else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Disable multiple models.
     */
    public function actionBulkDisable()
    {
        $this->setStatus($this->getModelName(), $_POST['id'], 0);
        $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
    }

    /**
     * Enable multiple models.
     */
    public function actionBulkEnable()
    {
        $this->setStatus($this->getModelName(), $_POST['id']);
        $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
    }
	public function actionBulkDelivered()
    {
        $this->setDelivered($this->getModelName(), $_POST['id'],4);
                $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
        
    }
	public function actionBulkNew()
    {
       	$this->setDelivered($this->getModelName(), $_POST['id'],1);
        $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
    }
    /**
     * Deletes multiple models.
     */
    public function actionBulkDelete()
    {
        if(Yii::app()->request->isPostRequest)
        {
            $this->beforeDelete($_POST['id']);
            $this->getModel()->deleteAllByAttributes(array(
                'id' => $_POST['id']
            ));
            $this->afterDelete($_POST['id']);

            $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
        }
        else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        /** @var $model BaseActiveRecord */
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['update'], array(
            'model' => $model,
        ));
    }

    public function actionIndex()
    {
        $this->redirect(array('admin'));
    }

    public function actionView($id)
    {
        $this->redirect(array('admin'));
    }

    /**
     * Actions that are always allowed.
     */
    public function allowedActions()
    {
        return 'login, error, logout';
    }

    /**
     * Get model via Model::model()
     *
     * @return BaseActiveRecord
     */
    public function getModel()
    {
        return call_user_func(array($this->getModelName(), 'model'));
    }

    public function getModelName()
    {
        return $this->modelName;
    }

    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * Get new model
     *
     * @param string $scenario
     * @throws CHttpException
     * @return BaseActiveRecord
     */
    public function getNewModel($scenario = 'insert')
    {
        if(!$this->modelName)
        {
            throw new CHttpException(500, Yii::t('backend', 'No model specified'));
        }

        if(!class_exists($this->modelName))
        {
            throw new CHttpException(500, Yii::t('backend', 'Model "{model}" not found',
                array('{model}' => $this->modelName)));
        }

        return new $this->modelName($scenario);
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
            'cancelled + create,update,clone',
        );
    }

    /**
     * Cancel button pressed
     *
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     */
    public function filterCancelled($filterChain)
    {
        if(isset($_POST['cancel']))
        {
            $this->redirect(array('admin'));
        }

        $filterChain->run();
    }

    /**
     * Add flash and redirect
     *
     * @param string $key Flash key
     * @param string $msg Flash message
     * @param array $url URL redirect to
     */
    public function flashRedirect($key, $msg, $url = array('admin'))
    {
        Yii::app()->user->addFlash($key, $msg);
        $this->redirect($url);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id the ID of the model to be loaded
     * @throws CHttpException
     * @return CActiveRecord
     */
    public function loadModel($id)
    {
        /** @var $model CActiveRecord */
        $model = $this->getModel()->findByPk($id);
        if($model === null)
        {
            throw new CHttpException(404, Yii::t('backend', 'The requested page does not exist.'));
        }

        return $model;
    }

    /**
     * Event called after delete actions
     * Don't forget call parent::afterDelete()
     *
     * @param int $data Model PID
     */
    protected function afterDelete($data)
    {
        /** @var $model BaseActiveRecord */
        $model = $this->getModel();
        if(method_exists($model, 'refreshCache'))
        {
            $model->refreshCache();
        }
    }

    /**
     * Set page size
     *
     * @param CAction $action
     * @return bool
     */
    protected function beforeAction($action)
    {
        if($data = Yii::app()->request->getParam('pageSize', null))
        {
            Yii::app()->user->setState('pageSize', $data);
        }

        return parent::beforeAction($action);
    }

    /**
     * Event called before delete actions
     * Don't forget call parent::beforeDelete()
     *
     * @param int $data Model PID
     */
    protected function beforeDelete($data)
    {
        $this->cleanFiles($data);
    }

    /**
     * Clean model files
     *
     * @param array $ids
     */
    protected function cleanFiles($ids)
    {
        if(!is_array($ids))
        {
            $ids = (array)$ids;
        }

        foreach($ids as $id)
        {
            /** @var $model FileAttachBehavior */
            $model = $this->getModel()->findByPk($id)->asa('attach');
            if($model)
                $model->cleanFiles(count($ids));
        }
    }

    /**
     * Do clone/create/update action
     *
     * @param BaseActiveRecord $model
     * @return bool
     */
    protected function doAction($model)
    {
        $action = $this->action->getId();

        if(isset($_POST[$this->getModelName()]))
        {
            $postData = $_POST[$this->getModelName()];

            /** @var $uploadModel FileAttachBehavior */
            $uploadModel = $this->getNewModel('upload')->asa('attach');
            $uploads = $uploadModel ? $uploadModel->saveUploads($postData) : null;

            $model->attributes = $postData;

            if(in_array($action, array('clone')))
            {
                $model->unsetAttributes(array('id'));
                $model->setIsNewRecord(true);
            }

            $model->setAttributes($uploads);
            $model->validate();

            if($uploadModel)
                $uploadModel->copyErrors($model);

            if(!$model->hasErrors() && $model->save(false))
                return $this->afterActionDone($model);

            Yii::app()->user->addFlash(
                'error',
                $this->renderPartial('//inc/_model_errors', array('data' => $model->stringifyAttributeErrors()), true)
            );
        }

        return false;
    }

    /**
     * Set model status
     *
     * @param string $modelName Model class name
     * @param array $ids Array of ID's to change status
     * @param int $status Status value (0|1)
     * @param string $field Table field name
     * @return int Number of updated records
     * @throws CHttpException
     */
    protected function setStatus($modelName, $ids, $status = 1, $field = 'id')
    {
        if(!call_user_func(array($modelName, 'model'))->hasAttribute('status'))
        {
            return 0;
        }

        if(Yii::app()->request->isPostRequest && !empty($ids))
        {
            $ids = !is_array($ids) ? (array)$ids : $ids;
            $criteria = new CDbCriteria();
            $criteria->addInCondition($field, $ids);
            $o = call_user_func(array($modelName, 'model'))->updateAll(
                array('status' => $status),
                $criteria
            );
            call_user_func(array($modelName, 'model'))->refreshCache();

            return $o;
        }
        else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
	
	 protected function setDelivered($modelName, $ids, $status = 1, $field = 'id')
    {
        if(!call_user_func(array($modelName, 'model'))->hasAttribute('orderstate_id'))
        {
            return 0;
        }

        if(Yii::app()->request->isPostRequest && !empty($ids))
        {
            $ids = !is_array($ids) ? (array)$ids : $ids;
            $criteria = new CDbCriteria();
            $criteria->addInCondition($field, $ids);
            $o = call_user_func(array($modelName, 'model'))->updateAll(
                array('orderstate_id' => $status),
                $criteria
            );
            call_user_func(array($modelName, 'model'))->refreshCache();

            return $o;
        }
        else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * After action done process
     * Method return used as caller return
     *
     * @param array $model Model
     * @return bool
     */
    protected function afterActionDone($model)
    {
        return true;
    }

    /**
     * Disabled action
     *
     * @throws CHttpException
     */
    protected function disabledAction()
    {
        throw new CHttpException(500, Yii::t('backend', 'This action disabled.'));
    }

    /**
     * Redirect action
     *
     * @param mixed $data Active record, string to return to or array
     */
    protected function redirectAction($data = null)
    {
        $returnUrl = request()->getParam('returnUrl');

        if(!$data && $returnUrl)
            $data = base64_decode($returnUrl);

        if(!$data)
            $this->redirect(array('admin'));

        if(is_string($data) || is_array($data))
            $this->redirect($data);

        if(is_object($data) && isset($data->id))
        {
            $args = array('update', 'id' => $data->id);
            if($returnUrl)
                $args['returnUrl'] = $returnUrl;
            $this->redirect($args);
        }

        $this->redirect(array('admin'));
    }

    /**
     * Performs the AJAX validation.
     *
     * @param CModel $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax'] === $this->getId().'-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}