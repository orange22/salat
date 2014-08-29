<?php
/**
 * @method dishImage getModel()
 */
class DishImageController extends BackController
{
    /**
     * Upload images
     *
     * @throws CHttpException
     */
    public function actionUpload()
    {
    	if(isset($_POST[$this->getModelName()]))
        {
            /** @var $dish dish */
            $dish = Dish::model()->findByPk($_POST[$this->getModelName()]['dish_id']);
            if(!$dish)
                throw new CHttpException(404, Yii::t('backend', 'Page not found.'));

            $model = $this->getNewModel();
            $model->attributes = $_POST[get_class($model)];

            /** @var $uploadModel FileAttachBehavior */
            $uploadModel = $this->getNewModel('upload');
            $uploadModel = $uploadModel->asa('attach');
            $uploads = $uploadModel ? $uploadModel->saveSimpleUpload() : null;

            $model->setAttributes($uploads);
            $model->validate();

            if($uploadModel)
                $uploadModel->copyErrors($model);

            if($model->hasErrors() || !$model->save(false))
                echo 'Error';
            echo 'Success';
        }
    }

    /**
     * Sort gallery images
     */
    public function actionSort()
    {
        if(isset($_POST[$this->getModelName()]))
        {
            $id = $_POST[$this->getModelName()]['dish_id'];
            $this->getModel()->updateSorting($id, $_POST[$this->getModelName()]['order']);
            $this->redirect(array('dish/update', 'id' => $id, '#' => 'form-upload'));
        }
    }

    public function filterCancelled($filterChain)
    {
        if(isset($_POST['cancel']))
        {
            $id = $_POST[$this->getModelName()]['dish_id'];
            $this->redirect(array('dish/update', 'id' => $id));
        }

        $filterChain->run();
    }

    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $this->loadModel($id)->delete();
            $this->afterDelete($id);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
}