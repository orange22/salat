<?php
if(isset($_COOKIE['__debug__atl']) || 1==1)
{
    $config['modules']['rights']['debug'] = true;
    $config['components']['db']['enableProfiling'] = true;
    $config['components']['db']['enableParamLogging'] = true;
    $config['components']['authManager']['showErrors'] = true;
    $config['components']['viewRenderer']['options']['debug'] = YII_DEBUG;
    $config['components']['viewRenderer']['extensions'][] = 'Twig_Extension_Debug';
    $config['components']['log'] = array(
        'class' => 'CLogRouter',
        'routes' => array(
            'web' => array(
                'class' => 'CWebLogRoute',
                'showInFireBug' => true,
                'enabled' => true,
            ),
            'profile' => array(
                'class' => 'CProfileLogRoute',
                'enabled' => false,
            ),
        )
    );
}