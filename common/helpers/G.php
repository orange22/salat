<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Get absolute URL
 *
 * @param string $relUrl
 * @return string
 */
function absUrl($relUrl)
{
    if(strpos($relUrl, 'http') !== false)
        return $relUrl;

    if($relUrl{0} !== '/')
        $relUrl = '/'.$relUrl;
    return Yii::app()->params['siteUrl'].$relUrl;
}

/**
 * Web application
 *
 * @return CWebApplication
 */
function app()
{
    return Yii::app();
}

/**
 * Client scripn
 *
 * @return CClientScript
 */
function cs()
{
    return Yii::app()->getClientScript();
}

/**
 * Date formatter
 *
 * @param string $pattern
 * @param int    $time
 * @return CDateFormatter
 */
function dateFormat($pattern, $time)
{
    return Yii::app()->dateFormatter->format($pattern, $time);
}

/**
 * Formatter
 *
 * @return CFormatter
 */
function formatter()
{
    return Yii::app()->getFormatter();
}

/**
 * User
 *
 * @return WebUser
 */
function user()
{
    return Yii::app()->getUser();
}

/**
 * Yii::app()->createUrl
 *
 * @param string $route
 * @param array $params
 * @param string $ampersand
 * @return string
 */
function url($route, $params = array(), $ampersand = '&')
{
    return Yii::app()->createUrl($route, $params, $ampersand);
}

/**
 * Echo encoded text
 *
 * @param string $text
 * @param bool   $return
 * @return string|void
 */
function e($text, $return = false)
{
    if(!$return)
        echo CHtml::encode($text);

    return CHtml::encode($text);
}

/**
 * CHtml::link
 *
 * @param string $text
 * @param string $url
 * @param array $htmlOptions
 * @return string
 */
function l($text, $url = '#', $htmlOptions = array())
{
    return CHtml::link($text, $url, $htmlOptions);
}

/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 *
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
function param($name, $default = null)
{
    if(isset(Yii::app()->params[$name]))
        return Yii::app()->params[$name];

    return $default;
}

/**
 * Yii::app()->request
 *
 * @return CHttpRequest
 */
function request()
{
    return Yii::app()->getRequest();
}

/**
 * Get cached data
 *
 * @param string $id
 * @param mixed $default
 * @return mixed
 */
function getCache($id, $default = null)
{
    if(!Yii::app()->cache)
        return $default;

    return Yii::app()->cache->get($id);
}

/**
 * Set cache
 *
 * @param string $id
 * @param mixed $value
 * @param int $expire
 * @param ICacheDependency $dependency
 * @return bool
 */
function setCache($id, $value, $expire = 0, $dependency = null)
{
    if(!Yii::app()->cache)
        return false;

    return Yii::app()->cache->set($id, $value, $expire, $dependency);
}