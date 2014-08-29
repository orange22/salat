<?php
if(2==1){
    error_reporting(1);
    @ini_set('display_errors',1);
    @ini_set('error_reporting', E_ALL);
}else{
    error_reporting(0);
    @ini_set('display_errors',0);
}
ini_set('memory_limit', '2048M');

@include(dirname(__FILE__).'/../init.php');

require_once(dirname(__FILE__).'/../common/lib/Yii/yii.php');

$config = CMap::mergeArray(
    require(dirname(__FILE__).'/../common/config/main.php'),
    require(dirname(__FILE__).'/../backend/config/main.php'),

    require(dirname(__FILE__).'/../common/config/main-local.php'),
    @include(dirname(__FILE__).'/../backend/config/main-local.php')
);

Yii::createWebApplication($config)->run();