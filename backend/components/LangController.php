<?php
/**
 * LanguageController
 * Base controller for language models
 */
class LangController extends BackController
{
    /**
     * Clone models
     *
     * @param int $pid Source model PID
     * @return void
     */
    public function actionClone($pid)
    {
        $models = $this->cloneModels($this->loadModelsByPid($pid));

        $this->performAjaxValidationTabular($models);

        $success = $this->doAction($models);
        $newPid = current($models)->pid;

        if($success)
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($newPid);
            }
            $this->redirectAction();
        }

        if($success !== null && $newPid)
        {
            $this->redirectAction($newPid);
        }

        $this->render($this->view['clone'], array(
            'pid' => $pid,
            'models' => $models,
            'model' => reset($models),
            'languages' => Language::getList(),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $models = $this->loadModelsByPid();

        $this->performAjaxValidationTabular($models);

        $success = $this->doAction($models);
        $newPid = current($models)->pid;

        if($success)
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($newPid);
            }
            $this->redirectAction();
        }

        if($success !== null && $newPid)
        {
            $this->redirectAction($newPid);
        }

        $this->render($this->view['create'], array(
            'models' => $models,
            'model' => reset($models),
            'languages' => Language::getList(),
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $pid the ID of the model to be updated
     */
    public function actionUpdate($pid)
    {
        $models = $this->loadModelsByPid($pid);

        $this->performAjaxValidationTabular($models);

        if($this->doAction($models))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($pid);
            }
            $this->redirectAction();
        }

        $model = reset($models);
        $this->render($this->view['update'], array(
            'pid' => $model->pid,
            'model' => $model,
            'models' => $models,
            'languages' => Language::getList(),
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     *
     * @param integer $pid the ID of the model to be deleted
     * @throws CHttpException
     * @return void
     */
    public function actionDelete($pid)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $this->beforeDelete($pid);
            $this->getModel()->deleteAllByAttributes(array('pid' => $pid));
            $this->afterDelete($pid);
            $this->getModel()->refreshCache();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
        }
        else
            throw new CHttpException(400, Yii::t('backend', 'Invalid request. Please do not repeat this request again.'));
    }

    /**
     * Deletes multiple models.
     */
    public function actionBulkDelete()
    {
        if(Yii::app()->request->isPostRequest)
        {
            $this->beforeDelete($_POST['pid']);
            $this->getModel()->deleteAllByAttributes(array(
                'pid' => $_POST['pid']
            ));
            $this->afterDelete($_POST['pid']);
            $this->getModel()->refreshCache();

            $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
        }
        else
            throw new CHttpException(400, Yii::t('backend', 'Invalid request. Please do not repeat this request again.'));
    }

    /**
     * Disable multiple models.
     */
    public function actionBulkDisable()
    {
        $this->setStatus($this->getModelName(), $_POST['pid'], 0, 'pid');
        $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
    }

    /**
     * Enable multiple models.
     */
    public function actionBulkEnable()
    {
        $this->setStatus($this->getModelName(), $_POST['pid'], 1, 'pid');
        $this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id the ID of the model to be loaded
     * @throws CHttpException
     * @return CModel
     */
    public function loadModel($id)
    {
        $model = $this->getModel()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, Yii::t('backend', 'The requested page does not exist.'));

        return $model;
    }

    /**
     * Returns the data model based on the PID key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $pid the ID of the model to be loaded
     * @throws CHttpException
     * @return array
     */
    public function loadModelsByPid($pid = 0)
    {
        $models = array();

        if($pid)
            $models = $this->getModel()->indexed()->findAllByAttributes(array('pid' => $pid));

        if($pid && empty($models))
            throw new CHttpException(404, Yii::t('backend', 'The requested page does not exist.'));

        // create all language models
        $languages = Language::getList();
        foreach($languages as $lang => $title)
        {
            if(isset($models[$lang]))
                continue;

            $models[$lang] = $this->getNewModel();
        }

        return $models;
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
            'itemAccess - admin',
            'checkLanguages + create, update, clone'
        ));
    }

    /**
     * Item access filter
     *
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @return void
     */
    public function filterItemAccess($filterChain)
    {
        $filterChain->run();
    }

    /**
     * Check if at least one language exists
     *
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     */
    public function filterCheckLanguages($filterChain)
    {
        if(count(Language::getList()) < 1)
        {
            Yii::app()->user->addFlash('error', Yii::t('backend', 'Add at least one language first.'));

            $this->redirect(array('admin'));
        }

        $filterChain->run();
    }

    /**
     * TinyMCE wrapper
     *
     * @param string $name Input name to attach to
     * @param bool $templates Add tinymce templates
     * @return string
     */
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

    /**
     * Event called after delete actions
     * Don't forget call parent::afterDelete()
     *
     * @param mixed $data Model PID or array of PIDs
     */
    protected function afterDelete($data)
    {
        /** @var $model LangActiveRecord */
        $model = $this->getModel();
        foreach((array)$data as $pid)
            Seo::model()->deleteMeta(array('pid' => $pid, 'entity' => $model->classId(true)));

        if(method_exists($model, 'refreshCache'))
            $model->refreshCache();

        if(array_key_exists('bpid', $model->relations()))
        {
            foreach((array)$data as $item)
                $model->deleteBasePid($item);
        }
    }

    /**
     * Event called before delete actions
     * Don't forget call parent::beforeDelete()
     *
     * @param mixed $data Model PID
     */
    protected function beforeDelete($data)
    {
        $this->cleanFiles($data);
    }

    /**
     * Clean model files
     *
     * @param array $pids
     */
    protected function cleanFiles($pids)
    {
        if(!is_array($pids))
            $pids = (array)$pids;

        foreach($pids as $pid)
        {
            /** @var $model FileAttachBehavior */
            $model = $this->getModel()->findByAttributes(array('pid' => $pid))->asa('attach');
            if($model)
                $model->cleanFiles();
        }
    }

    /**
     * Clone models
     * Clone existing models or create new
     *
     * @param array $models
     * @return array
     */
    protected function cloneModels($models)
    {
        $o = array();
        foreach(Language::getList() as $lang => $title)
        {
            if(!isset($models[$lang]))
                $o[$lang] = $this->getNewModel();
            else
                $o[$lang] = clone $models[$lang];
            $o[$lang]->setScenario('create');
        }

        return $o;
    }

    /**
     * Do clone/create/update action
     *
     * @param array $models Array of models
     * @return bool
     */
    protected function doAction($models)
    {
        $action = $this->action->getId();

        $pid = 0;
        if(isset($_POST[$this->getModelName()]))
        {
            $res = true;
            $errors = array();
            $postData = $_POST[$this->getModelName()];

            /** @var $uploadModel FileAttachBehavior */
            $uploadModel = $this->getNewModel('upload')->asa('attach');
            $uploads = $uploadModel ? $uploadModel->saveUploads($postData) : null;

            foreach(Language::getList() as $lang => $title)
            {
                /** @var $model LangActiveRecord */
                $model = $models[$lang];
                // set language dependent attributes
                if(isset($postData[$lang]))
                    $model->attributes = $postData[$lang];

                // set language independent attributes
                foreach($model->fixedAttributes() as $fixedAttr)
                {
                    if(!array_key_exists($fixedAttr, $postData))
                        continue;
                    $model->setAttribute($fixedAttr, $postData[$fixedAttr]);
                }

                // force new AR
                if(in_array($action, array('clone')))
                {
                    $model->unsetAttributes(array('id'));
                    $model->setIsNewRecord(true);
                }

                // set file attributes
                $model->setAttributes($uploads);
                if($uploadModel)
                    $model->setAttributes($uploadModel->saveUploads($postData, $lang));

                // set PID
                if(in_array($action, array('clone', 'create')))
                    $model->setAttribute('pid', $pid);

                $model->validate();
                if($uploadModel)
                    $uploadModel->copyErrors($model);

                if($model->hasErrors() || !$model->save(false))
                {
                    $res = false;

                    $errors = CMap::mergeArray($errors, $model->stringifyAttributeErrors());

                    // break further processing if first model failed because we do not have PID
                    if(!$pid && in_array($action, array('clone', 'create')))
                        break;
                }

                if(!$pid)
                {
                    if(in_array($action, array('clone', 'create')))
                        $pid = $model->getBoundedPid();
                    if(in_array($action, array('update')))
                        $pid = $model->pid;
                }
            }

            if(!empty($errors))
                Yii::app()->user->addFlash('error', $this->renderPartial('//inc/_model_errors_i18n', array('data' => $errors), true));

            if($res)
            {
                return $this->afterActionDone($models);
            }

            return false;
        }

        return null;
    }

    /**
     * Performs the AJAX validation of multiple models
     *
     * @param array $models
     */
    protected function performAjaxValidationTabular($models)
    {
        if(isset($_POST['ajax']) && $_POST['ajax'] === $this->getId().'-form')
        {
            echo ActiveForm::validateTabular($models);
            Yii::app()->end();
        }
    }

    /**
     * After action done process
     * Method return used as caller return
     *
     * @param array $models Array of models
     * @return bool
     */
    protected function afterActionDone($models)
    {
        return true;
    }

    /**
     * Redirect action
     *
     * @param mixed $data Active record, string to return to or array
     */
    protected function redirectAction($data = null)
    {
        if(!$data)
            parent::redirectAction();

        if(is_numeric($data))
            $this->redirect(array('update', 'pid' => $data));

        if(is_object($data) && isset($data->pid))
            $this->redirect(array('update', 'pid' => $data->id));

        parent::redirectAction($data);
    }
}