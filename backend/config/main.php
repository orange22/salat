<?php
Yii::setPathOfAlias('backend', ROOT.'backend');
Yii::setPathOfAlias('frontend', ROOT.'frontend');
Yii::setPathOfAlias('application', ROOT.'backend');
$config = array(
    'basePath' => ROOT.'backend',
    'name' => 'Admin',
    'preload' => array(
        'bootstrap'
    ),
    'import' => array(
        'backend.models.*',
        'backend.components.*',
        'backend.extensions.bootstrap',
        'backend.extensions.bootstrap.widgets.*',
        'common.extensions.yii-mail.*'
    ),
    'modules' => array(
        'elfinder' => array()
    ),
    'components' => array(
        'request' => array(
            'csrfTokenName' => 'btoken',
        ),
        'urlManager' => array(
            'urlFormat' => 'get',
            'showScriptName' => true
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'assetManager' => array(
            'baseUrl' => '/backend/assets',
            'basePath' => 'backend/assets',
        ),
        'bootstrap' => array(
            'class' => 'backend.extensions.bootstrap.components.Bootstrap',
            'responsiveCss' => true
        ),
        'ih' => array(
            'class' => 'backend.extensions.CImageHandler'
        ),
        'uploader' => array(
            'class' => 'common.components.Uploader',
            'subdirs' => 1
        ),
    ),

    'controllerMap' => array(
        'color' => 'backend.controllers.LiteralController',
        'country' => 'backend.controllers.LiteralController',
        'apparel' => 'backend.controllers.LiteralController',
    )
);

return $config;