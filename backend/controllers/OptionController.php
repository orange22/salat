<?php
class OptionController extends BackController
{
    /**
     * Clone model
     *
     * @param int $id Source model
     * @return void
     */
    public function actionClone($id)
    {
        $model = clone $this->loadModel($id);
        $model->setScenario('create');

        $model->unsetAttributes(array('id'));
        $this->performAjaxValidation($model);

        if(isset($_POST['Option']))
        {
            $model->setIsNewRecord(true);
            $model->attributes = $_POST['Option'];

            if($model->save())
            {
                if(isset($_POST['apply']))
                {
                    $this->redirect(array('update', 'id' => $model->getPrimaryKey()));
                }
                $this->redirect(array('admin'));
            }
        }

        $this->render(
            'clone', array(
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
        $model = new Option('create');

        $this->performAjaxValidation($model);

        if(isset($_POST['Option']))
        {
            $model->attributes = $_POST['Option'];
            if($model->save())
            {
                if(isset($_POST['apply']))
                {
                    $this->redirect(array('update', 'id' => $model->getPrimaryKey()));
                }
                $this->redirect(array('admin'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if(isset($_POST['Option']))
        {
            $model->attributes = $_POST['Option'];
            if($model->save())
            {
                if(isset($_POST['apply']))
                {
                    $this->redirect(array('update', 'id' => $model->getPrimaryKey()));
                }
                $this->redirect(array('admin'));
            }
        }

        $this->render('update', array(
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
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
            {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function filters()
    {
        return CMap::mergeArray(array(
            'updateAllowed + update'
        ), parent::filters());
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id the ID of the model to be loaded
     * @throws CHttpException
     * @return Option
     */
    public function loadModel($id)
    {
        $model = Option::model()->findByPk($id);
        if($model === null)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param CModel $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'option-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Allow update only availale option
     *
     * @param CFilterChain $filterChain
     */
    public function filterUpdateAllowed($filterChain)
    {
        $model = $this->loadModel(Yii::app()->request->getQuery('id'));
        if(Yii::app()->user->checkAccess('Option.Update', array('role' => $model->role)))
        {
            $filterChain->removeAt(1);
        }

        $filterChain->run();
    }
}