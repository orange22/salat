<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).DS.'..'.DS.'..'.DS);

Yii::setPathOfAlias('root', ROOT);
Yii::setPathOfAlias('common', ROOT.'common');
require(ROOT.'common/helpers/G.php');

$config = array(
    'preload' => array('log'),
    'import' => array(
        'common.models.*',
        'common.components.*',
        'common.modules.rights.*',
        'common.modules.rights.components.*',
        'common.extensions.yii-eauth.*',
        'common.extensions.yii-eauth.lib.*',
        'common.extensions.lightopenid.*',
        'common.extensions.yii-eauth.*',
        'common.extensions.yii-eauth.services.*',
    ),
    'modules' => array(
        'rights' => array(
            'class' => 'common.modules.rights.RightsModule',
            'superuserName' => 'admin',
            'authenticatedName' => 'authenticated',
            'userIdColumn' => 'id',
            'userNameColumn' => 'login',
            'layout' => 'root.backend.views.rights.layouts.main',
            'appLayout' => 'root.backend.views.layouts.main',
        ),
    ),
    'components' => array(
    	'user' => array(
            'class' => 'WebUser',
            'loginUrl' => array('/site/login'),
            'stateKeyPrefix' => '123',
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'db' => array(
            'connectionString' => 'mysql:host=;dbname=',
            'username' => '',
            'password' => '',
            'schemaCachingDuration' => 86400,
            'emulatePrepare' => true,
            'charset' => 'utf8',
            'tablePrefix' => 'gs_',
        ),
        'request' => array(
            'class' => 'HttpRequest',
            'enableCsrfValidation' => true,
            'csrfTokenName' => 'token',
            'enableCookieValidation' => true,
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'authManager' => array(
            'class' => 'root.common.components.DbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'gs_auth_item',
            'itemChildTable' => 'gs_auth_item_child',
            'assignmentTable' => 'gs_auth_assignment',
            'rightsTable' => 'gs_rights',
        ),
       /* 'cache' => array(
            'class' => 'system.caching.CDummyCache',
        ),*/
        'mail' => array(
            'class' => 'common.extensions.yii-mail.YiiMail',
            'transportType' => 'php',
            'logging' => false,
            'dryRun' => false
        ),
        'eauth' => array(
            'class' => 'common.extensions.yii-eauth.EAuth',
            'popup' => false, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache'.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'services' => array( // You can change the providers and their classes.
                'facebook' => array(
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'FacebookOAuthService',
                    'client_id' => '509418155763464',
                    'client_secret' => '42e6366c20836b90edf1fa3208ceb075',
                ),
            ),
        ),
    ),

    'behaviors' => array(
        'appConfigBehavior'
    ),

    'params' => array(
        // relative to Yii::app()->basePath/..
        'webRoot' => 'lpovar.com.ua',
        'adminEmail' => 'info@lpovar.com.ua',
        'cacheDuration' => 3600,
        'uploadUrl' => 'upload',
    ),
);

return $config;