<?php
require_once(dirname(__FILE__).'/../framework/yii.php');
$yiic = dirname(__FILE__).'/../framework/yiic.php';

$config = CMap::mergeArray(
    require(dirname(__FILE__).'/../common/config/main.php'),
    require(dirname(__FILE__).'/../console/config/main.php')
);
$config = CMap::mergeArray($config, require(dirname(__FILE__).'/../common/config/main-local.php'));

require_once($yiic);
