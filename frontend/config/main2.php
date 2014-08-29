<?php
Yii::setPathOfAlias('frontend', ROOT.'frontend');
Yii::setPathOfAlias('www', ROOT.'../..');
$config = array(
    'basePath' => ROOT.'frontend',
    'name' => 'Frontend',
    'theme' => '',
    'import' => array(
        'frontend.models.*',
        'frontend.components.*',
    ),
    'components' => array(
        'request' => array(
            'enableCsrfValidation' => false,
            'csrfTokenName' => 'ftoken',
        ),
        'detectMobileBrowser' => array(
            'class' => 'frontend.extensions.yii-detectmobilebrowser.XDetectMobileBrowser',
        ),
       /*
        'user' => array(
                   'class' => 'WebUser',
                   'loginUrl' => array('/site/login'),
                   'allowAutoLogin' => true,
               ),*/

        'urlManager' => array(
           /* 'class' => 'frontend.components.UrlManager',*/
            'urlFormat' => 'path',
            'showScriptName' => false,
            /*'vars' => array('page', 'p', 'id', 'q'),*/
            'rules' => array(
                /*
                array(
                                    'class' => 'UrlRule',
                                    'actions' => array()
                                ),*/


               /*
                '<lang:\w{2}>/<controller:\w+>' => '<controller>',
                               '<lang:\w{2}>/<controller:\w+>/<id:\d+>' => '<controller>/view',
                               '<lang:\w{2}>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                               '<lang:\w{2}>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',*/

                'product/<action:\w+>/<id:\d+>' => 'dish/<action>',
                '<controller:\w+>' => '<controller>',
                '<controller:\w+>/<id:\d+>/<title:\w+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/page/<page:\d+>' => '<controller>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

                'page/<code:\w+>'=>'page/view',
                'site/message/<message:\w+>'=>'site/message',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',




            ),
        ),
        'themeManager' => array(
            'basePath' => Yii::getPathOfAlias('frontend'),
            'baseUrl' => Yii::getPathOfAlias('www')
        ),
        'viewRenderer' => array(
            'class' => 'frontend.extensions.twig-renderer.ETwigViewRenderer',

            // All parameters below are optional, change them to your needs
            'fileExtension' => '.twig',
            'options' => array(
                'autoescape' => true,
            ),
            'extensions' => array(
            ),
            'globals' => array(
                'html' => 'CHtml'
            ),
            'functions' => array(
                't' => 'Yii::t',
                'fileUrl' => 'File::fileUrl',
                'file' => 'File::htmlLinkFile',
                'image' => 'File::htmlImageEx',
                'opt' => 'Option::getOpt',
                'parseTime' => 'Tool::parseTime',
                'hasImage' => 'Tool::hasImage',
                'embed' => 'Tool::embedVideo',
                'keyDefined' => 'Tool::keyDefined',
            ),
            'filters' => array(
                'image' => array('File::htmlImageEx', array('is_safe' => array('html'))),
                'fileUrl' => array('File::fileUrl', array('is_safe' => array('html'))),
                'ext' => array('File::extensionName', array('is_safe' => array('html'))),
                'email' => array('Tool::obfuscateEmailJs', array('is_safe' => array('html'))),
                'autop' => array('Tool::autop', array('pre_escape' => 'html', 'is_safe' => array('html'))),
                'url' => array('Tool::url', array('pre_escape' => 'html', 'is_safe' => array('html'))),
                'price' => array('Tool::nicePrice', array('pre_escape' => 'html', 'is_safe' => array('html'))),
                'nicePrice' => array('Tool::nicePrice', array('pre_escape' => 'html', 'is_safe' => array('html'))),
            ),
        ),

    ),
);

return $config;