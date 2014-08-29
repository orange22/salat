<?php
class UserController extends BackController
{
    public function actionClone($id)
    {
        /** @var $model User */
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
            'authItemModel' => isset($model->authItems) ? current($model->authItems) : new AuthItem()
        ));
    }
	public function CountOrders($rowId, $data)
    {
    	//echo count($data);
    	CVarDumper::dump($data,10,true);
    	//return 5;
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
            'authItemModel' => new AuthItem()
        ));
    }

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
            'authItemModel' => isset($model->authItems[0]) ? $model->authItems[0] : new AuthItem()
        ));
    }
	protected function afterActionDone($model)
    {
        /** @var $model Product */
       
        $model->junction->attach($model);
        $pcData = isset($_POST['User']['userUsertypes']) ? array_unique($_POST['User']['userUsertypes']) : array();
        $model->junction->updateRelated('userUsertypes', $pcData);

         return parent::afterActionDone($model);
    }
}