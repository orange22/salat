<?php

class SiteController extends BackController
{
    public function actionAdmin()
    {
        if(user()->checkAccess('Order.*'))
            $this->redirect(array('/order'));
        elseif(user()->checkAccess('Blog.*'))
            $this->redirect(array('/blog'));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->redirect(array('/order'));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionInit()
    {
        $sql = Yii::app()->db->createCommand();
        $sql->select()
            ->from('{{user}}')
            ->order('id')
            ->limit(1, 0);

        if($data = $sql->queryRow())
        {
            echo 'There are already users found.<br />';
            $userId = $data['id'];
        }
        else
        {
            $sql->reset();

            $pwdHasher = new PasswordHash(8, false);
            $password = $pwdHasher->HashPassword('123456');

            $res = $sql->insert('{{user}}', array(
                'login' => 'admin',
                'password' => $password,
                'email' => 'admin@localhost.com',
                'name' => 'admin',
            ));

            if($res)
                echo 'User generated.<br />';
            $userId = app()->db->getLastInsertID();
        }

        $sql->reset();
        app()->db->createCommand('SET AUTOCOMMIT=0')->execute();
        $sql->truncateTable('{{auth_item}}');
        echo 'Auth items truncated.<br />';
        $sql->reset();
        $res = $sql->insert('{{auth_item}}', array(
            'name' => 'admin',
            'type' => 2,
        ));

        if($res)
            echo 'Auth item added.<br />';

        $sql->truncateTable('{{auth_assignment}}');
        echo 'Auth assignment truncated.<br />';
        $sql->reset();
        $res = $sql->insert('{{auth_assignment}}', array(
            'itemname' => 'admin',
            'userid' => $userId,
        ));

        if($res)
            echo 'User role assigned.<br />';

        $sql->reset();
        $sql->truncateTable('{{auth_log}}');
        echo 'Auth log truncated.<br />';
        app()->db->createCommand('SET AUTOCOMMIT=1')->execute();
    }

    public function actionPurgeCache()
    {
        if(Yii::app()->cache)
            Yii::app()->cache->flush();
    }
}