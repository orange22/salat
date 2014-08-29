<?php

class DefaultController extends BackController
{

    public function actionConnector()
    {
        $this->layout = false;

        Yii::import('elfinder.vendors.*');
        require_once('elFinder.class.php');
		
        $opts = array(
            'root' => dirname(__FILE__).DIRECTORY_SEPARATOR
                .'..'.DIRECTORY_SEPARATOR
                .'..'.DIRECTORY_SEPARATOR
                .'..'.DIRECTORY_SEPARATOR
                .'..'.DIRECTORY_SEPARATOR
                .'lpovar.com.ua'.DIRECTORY_SEPARATOR
                .'upload'.DIRECTORY_SEPARATOR
                .'content'.DIRECTORY_SEPARATOR
        ,
            'URL' => Yii::app()->baseUrl.'/upload/content/',
            'rootAlias' => 'Upload',
            'uploadAllow' => array('swf',
                'gz', 'tgz', 'bz', 'bz2', 'tbz', 'zip', 'rar', 'tar', '7z',
                'txt', 'rtf', 'pdf',
                'bmp', 'jpg', 'jpeg', 'gif', 'png', 'tif', 'tiff', 'tga', 'psd',
                'mp3', 'mid', 'ogg', 'mp4a', 'wav',
                'wma', 'avi', 'dv', 'mp4', 'mpeg', 'mpg', 'mov', 'wm', 'flv', 'mkv',
            )
        );

        $fm = new elFinder($opts);
        $fm->run();
    }
}