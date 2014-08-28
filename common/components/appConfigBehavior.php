<?php
/**
 * Web application behavior for runtime configuration settings
 */
class appConfigBehavior extends CBehavior
{
    /**
     * Declare events and theirs handlers
     *
     * @return array
     */
    public function events()
    {
        return array_merge(parent::events(), array(
            'onBeginRequest' => 'beginRequest',
        ));
    }

    /**
     * onBeginRequest handler
     *
     * @throws CHttpException
     * @return void
     */
    public function beginRequest()
    {
       
        $this->owner->language = 'ru';
               Yii::app()->setLanguage($this->owner->language);
       

        if(!isset(Yii::app()->params['siteUrl']) && isset($_SERVER['SERVER_NAME']))
        {
            Yii::app()->params['siteUrl'] = 'http://'.CHtml::encode($_SERVER['SERVER_NAME']);
            if($_SERVER['SERVER_PORT'] != 80)
                Yii::app()->params['siteUrl'] .= ':'.$_SERVER['SERVER_PORT'];
        }

        if(!Yii::app()->params['siteUrl'] || Yii::app()->params['siteUrl']{0} === ':')
            throw new CHttpException(500, Yii::t('backend', 'Cannot resolve site url'));

        $this->restoreSession();
        Yii::app()->db->createCommand('SET AUTOCOMMIT=1')->execute();
    }

    /**
     * If using CSRF validation
     * restore session from ID passed by _POST
     *
     * @return void
     */
    protected function restoreSession()
    {
        if(!Yii::app()->request->enableCsrfValidation)
            return;

        if(isset($_POST['SESSION_ID'], $_POST[Yii::app()->request->csrfTokenName]))
        {
            /** @var $session CHttpSession */
            $session = Yii::app()->session;
            $session->close();
            $session->sessionID = $_POST['SESSION_ID'];
            $session->open();

            Yii::app()->request->csrfToken = Yii::app()->session['csrfToken'];
            Yii::app()->request->cookies[Yii::app()->request->csrfTokenName] = new CHttpCookie(
                Yii::app()->request->csrfTokenName,
                $session['csrfToken']
            );
        }
		Yii::app()->session['csrfToken'] = Yii::app()->request->csrfToken;
        return;
    }
}