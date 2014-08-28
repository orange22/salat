<?php
$config = array(
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=salat',
            'schemaCachingDuration' => 86400,
            'username' => 'u_bozhok',
            'password' => 'bozhok1984',
        ),
        'cache' => array(
            'class' => 'system.caching.CFileCache',
            'keyPrefix' => 'default',
            'cachePath' => Yii::getPathOfAlias('common.runtime.cache')
        ),
    ),
    'params' => array(
        'noreply' => 'no-reply@lpovar.com.ua',
    )
);

@include('dev-local.php');

return $config;