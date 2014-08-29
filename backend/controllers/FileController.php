<?php
class FileController extends BackController
{
    /**
     * Register file from file system
     */
    public function actionRegisterFile()
    {
        if(!request()->isPostRequest || !isset($_POST['model']) || !isset($_POST['path']))
            app()->end();

        try
        {
            $allowedExt = array();

            $model = call_user_func(array($_POST['model'], 'model'));
            if(!$model)
                throw new CHttpException(500, Yii::t('backend', 'Model not found'));

            foreach($model->rules() as $rule)
            {
                if($rule[1] !== 'file')
                    continue;

                $allowedExt = $rule['types'];
                break;
            }

            $file = File::model()->register($_POST['path'], $allowedExt);

            echo json_encode(array(
                'id' => $file->getPrimaryKey(),
                'url' => $file->isImage()
                    ? File::getImageWithLink($file, '', array('width' => 100))
                    : File::getFile($file),
            ));
        }
        catch(CException $e)
        {
            throw new CHttpException(500, $e->getMessage());
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
        $model = $this->loadModel($id);

        if(isset($_POST['File']))
        {
            $model->attributes = $_POST['File'];
            if($model->save(true, array('width', 'height')))
            {
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

    public function actionDeleteUnused()
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            File::model()->deleteAllUnused();

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

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new File('search');
        $model->unsetAttributes(); // clear any default values
        if(isset($_GET['File']))
        {
            $model->attributes = $_GET['File'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes multiple models.
     */
    public function actionBulkDelete()
    {
        if(Yii::app()->request->isPostRequest)
        {
            foreach($_POST['id'] as $id)
            {
                try
                {
                    $this->loadModel($id)->delete();
                }
                catch(CException $e)
                {
                    Yii::app()->user->addFlash('error', $e->getMessage());
                }
            }

            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id the ID of the model to be loaded
     * @return File
     */
    public function loadModel($id)
    {
        $model = File::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'file-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}