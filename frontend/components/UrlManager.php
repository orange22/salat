<?php
/**
 * UrlManager
 */
class UrlManager extends CUrlManager
{
    /**
     * Known GET variable names
     *
     * @var array
     */
    public $vars = array();

    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if(!isset($params['lang']))
            $params['lang'] = Yii::app()->language;

        return parent::createUrl($route, $params, $ampersand);
    }
}