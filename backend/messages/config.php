<?php
return array(
    'sourcePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'messagePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages',
    'languages' => array('ru'),
    'fileTypes' => array('php'),
    'overwrite' => true,
    'exclude' => array(
        '.svn',
        '.git',
        '/config',
        '/extensions',
        '/gii',
        '/messages',
        '/modules',
        '/vendors',
        '/runtime',
    ),
);