<?php
return array(
    'sourcePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common',
    'messagePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages',
    'languages' => array('ru'),
    'fileTypes' => array('php'),
    'overwrite' => true,
    'exclude' => array(
        '.svn',
        '.git',
        '/config',
        '/data',
        '/extensions',
        '/gii',
        '/lib',
        '/migrations',
        '/messages',
        '/modules',
        '/vendors',
        '/runtime',
    ),
);