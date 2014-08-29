<?php
/**
 * Options form controller
 */
class OptionsController extends RController
{
    /*
    public function actionAdmin()
        {
            $this->redirect(array('/menu'));
        }*/
    

    public function actionOptionGroup($group)
    {
        $group = trim(urldecode($group));
        if(!$group)
            throw new CHttpException(404, Yii::t('cp', 'Options group not found.'));

        $this->renderForm($group);
    }

    /**
     * Actions that are always allowed.
     */
    public function allowedActions()
    {
        return 'login, error';
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
            'cancelled + update',
        );
    }

    /**
     * Cancel button pressed
     *
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     */
    /*
    public function filterCancelled($filterChain)
        {
            if(isset($_POST['cancel']))
            {
                $this->redirect(array('/menu'));
            }
    
            $filterChain->run();
        }*/
    

    protected function renderForm($group, $tpl = 'form')
    {
        $options = Option::model()->indexed()->sort()->findAll('`group` = :group', array(':group' => $group));
        if(!$options)
            throw new CHttpException(404, Yii::t('cp', 'Options group not found.'));
        $this->setPageTitle(app()->name.' - '.Yii::t('cp', $group));

        $model = new OptionsForm($options);
        if(isset($_POST['Options']))
        {
            if(!$model->save($_POST['Options']))
            {
                $msg = array();
                foreach($model->getErrors() as $title => $errors)
                    $msg[$title] = count($errors) == 1 ? $errors[0] : implode("; ", $errors);
                Yii::app()->user->addFlash('error', implode('<br />', $msg));
                $this->refresh();
            }

            if(isset($_POST['apply']))
                $this->refresh();
            //$this->redirect(array('/menu'));
        }

        $name = preg_replace('/[^a-z0-9_]/', '', str_replace(array(' ', '-'), '_', strtolower($group)));
        $this->render($tpl, array(
            'name' => $name,
            'model' => $model,
            'group' => $group,
            'tplVars' => method_exists($this, "data{$name}") ? (array)$this->{"data{$name}"}() : array()
        ));
    }

    
}
