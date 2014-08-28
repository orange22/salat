<?php
/**
 * Url Rules parsing/creating
 */
class UrlRule extends CBaseUrlRule
{
    /**
     * Additional app actions
     *
     * @var array
     */
    protected $actions = array();

    /**
     * @param array $actions
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    /**
     * Create URL
     *
     * @param CUrlManager $manager
     * @param string $route
     * @param array $params
     * @param string $ampersand
     * @throws CHttpException
     * @return mixed|string
     */
    public function createUrl($manager, $route, $params, $ampersand)
    {
        $url = $route;
        $params = array_filter($params);
        if(isset($params['page']))
            $url = $this->siteUrl($route, $params);

        if(strpos($url, 'site/') === 0)
            $url = $this->indexUrl($url, $params);

        if($this->hasHostInfo)
        {
            $hostInfo = Yii::app()->getRequest()->getHostInfo();
            if(stripos($url, $hostInfo) === 0)
                $url = substr($url, strlen($hostInfo));
        }

        $url = CHtml::encode($url);
        if(empty($params))
            return $url;

        $url .= '?'.$manager->createPathInfo($params, '=', $ampersand);

        return $url;
    }

    /**
     * Parse URL
     *
     * @param CUrlManager $manager
     * @param CHttpRequest $request
     * @param string $pathInfo
     * @param string $rawPathInfo
     * @throws CHttpException
     * @return mixed
     */
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        unset($_GET['site'], $_GET['page'], $_GET['id'], $_GET['path']);
        // <lang>/<path>
        if(!preg_match('#^([a-z]{2})/(?:(.*))?$#i', $pathInfo, $m))
        {
            if($pathInfo === '' || (strlen($pathInfo) == 2 && Language::exist($pathInfo)))
            {
                $_REQUEST['lang'] = $_GET['lang'] = $pathInfo;

                Yii::app()->setLanguage(Language::getActive());
                $_REQUEST['lang'] = $_GET['lang'] = Yii::app()->language;
                $_REQUEST['r'] = $_GET['r'] = '';

                return '';
            }
            throw new CHttpException(404, Yii::t('frontend', 'Page not found.'));
        }

        $_REQUEST['lang'] = $_GET['lang'] = $m[1];

        $parts = array_filter(explode('/', $m[2]));
        array_unshift($parts, '/');

        Yii::app()->setLanguage(Language::getActive());

        // resolve page
        if(!($pageData = PageFront::resolvePage($parts)))
            throw new CHttpException(404, Yii::t('frontend', 'Page not found.'));

        $parts = $pageData['unresolvedPath'];
        $page = $pageData['page'];
        $site = $pageData['site'];

        $_REQUEST['page'] = $_GET['page'] = $page['pid'];
        $_REQUEST['site'] = $_GET['site'] = $site['pid'];

        if($page['handler'])
        {
            list($controller, $action) = array_pad(explode('/', $page['handler']), 2, null);
            $action = !isset($action) ? 'index' : $action;
        }
        else
        {
            $action = 'page';
            $controller = 'site';
        }

        // <id>[/<stuff>]
        if(($c = count($parts)))
        {
            // if last element is ID-like, consider view action
            $id = array_shift($parts);
            if(is_numeric($id))
            {
                --$c;
                $action = 'view';
                $_REQUEST['id'] = $_GET['id'] = $id;

                // <stuff>
                if($c > 0)
                    throw new CHttpException(404, Yii::t('frontend', 'Page not found.'));
            }
            else
            {
                if(in_array($id, $this->actions))
                {
                    $action = $id;
                }
                else
                {
                    throw new CHttpException(404, Yii::t('frontend', 'Page not found.'));
                }
            }
        }

        $_REQUEST['r'] = $_GET['r'] = $controller.'/'.$action;

        return $controller.'/'.$action;
    }

    /**
     * Generate url based on site,page IDs
     *
     * @param string $route Route
     * @param array $params URL params
     * @throws CHttpException
     * @return string
     */
    protected function siteUrl($route, &$params)
    {
        $url = array();
        if(isset($params['lang']))
        {
            $url[] = $params['lang'];
            unset($params['lang']);
        }

        if(!($page = PageFront::pathToPage($params['page'])))
        {
            Yii::log("PageFront::pathToPage({$params['page']}) failed.", CLogger::LEVEL_WARNING, get_class($this).'.'.__FUNCTION__);
            unset($params['page']);

            return 'site/index';
        }
        $url[] = $page;
        unset($params['page'], $params['site']);

        if(isset($params['path']))
        {
            $url[] = trim($params['path'], '/ ');
            unset($params['path']);
        }

        // add specific action to url
        $parts = array_filter(explode('/', trim($route, '/ ')));
        if(isset($parts[1]) && in_array($parts[1], $this->actions))
        {
            $url[] = $parts[1];
        }

        if(isset($params['id']))
        {
            $url[] = $params['id'];
            unset($params['id']);
        }

        $url = implode('/', $url);

        return $url;
    }

    /**
     * Build index page url
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    protected function indexUrl($route, &$params)
    {
        $url = array();
        if(isset($params['lang']))
        {
            $url[] = $params['lang'];
            unset($params['lang']);
        }

        // add specific action to url
        $parts = array_filter(explode('/', trim($route, '/ ')));
        if(isset($parts[1]) && in_array($parts[1], $this->actions))
            $url[] = $parts[1];

        return implode('/', $url);
    }
}