<?php
Yii::setPathOfAlias('frontend', ROOT.'frontend');
Yii::setPathOfAlias('www', ROOT.'../..');
$config = array(
    'id' => 'frontend',
    'basePath' => ROOT.'frontend',
    'name' => 'Салатник',
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
        'urlManager' => array(
            'class' => 'frontend.components.UrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'vars' => array('page', 'p', 'id', 'q'),
            'rules' => array(
                '' => 'site/index',
                '<controller:\w+>' => '<controller>',
                'category/<category:\w+>' => 'category/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'clientScript' => array(
            'class' => 'common.components.ClientScript',
            'wwwPath' => Yii::getPathOfAlias('salatnyk.com.ua'),
            //'cacheTime' => 3600 * 24,
            'cacheTime' => 0,
            'scriptMap' => array(
            ),
            'packages' => array(
                'base' => array(
                    'baseUrl' => '/',
                    'js' => array_filter(array(
                        'js/jquery-1.10.2.min.js',
                        'js/jquery.validate.js',
                        'js/jquery.scrollTo.js',
                        'js/jquery.nav.js',
                        'js/jquery.main.js'
                    )),
                    'css' => array_filter(array(
                        'css/all.css'
                    )),
                ),
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
                'html' => 'CHtml',
            ),
            'functions' => array(
                't' => 'Yii::t',
                'text_limit' => 'Tool::text_limit',
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
                'translit' => array('Transliteration::text', array('pre_escape' => 'html', 'is_safe' => array('html'))),
            ),
        ),
    ),
);

return $config;