<?php
/**
 * Class ClientScript
 *
 * Improved packages. Added per package position.
 * Auto-versioning based on file last modification time
 */
class ClientScript extends CClientScript
{
    /**
     * Path to web root directory
     *
     * @var string
     */
    public $wwwPath = '';

    /**
     * Whether to add css/js version
     *
     * @var bool
     */
    public $enableVersioning = true;

    /**
     * CSS/JS caching state ID
     *
     * @var string
     */
    public $cacheStateId = 'cssjs';

    /**
     * CSS/JS caching time to refresh
     * 0 - disable, debug mode or explicit cleaning required to refresh
     *
     * @var int
     */
    public $cacheTime = 0;

    public function render(&$output)
    {
        if(!$this->hasScripts)
            return;

        $this->renderCoreScripts();

        if(!empty($this->scriptMap))
            $this->remapScripts();

        $this->unifyScripts();
        if($this->enableVersioning)
            $this->refreshCache();

        $this->renderHead($output);
        if($this->enableJavaScript)
        {
            $this->renderBodyBegin($output);
            $this->renderBodyEnd($output);
        }
    }

    public function renderCoreScripts()
    {
        if($this->coreScripts === null)
            return;
        $cssFiles = array();
        $jsFiles = array();
        $ctrl = Yii::app()->controller;
        foreach($this->coreScripts as $name => $package)
        {
            $pos = isset($package['position']) ? $package['position'] : $this->coreScriptPosition;
            $baseUrl = $this->getPackageBaseUrl($name);
            if(!empty($package['js']))
            {
                foreach($package['js'] as $js)
                {
                    if(is_array($js))
                    {
                        $jsFiles[$pos][$ctrl->createUrl($js[0], array_slice($js, 1))] = $baseUrl.'/'.$js[0];
                    }
                    else
                    {
                        if(strpos($js, '//') !== false)
                            $jsFiles[$pos][$js] = $js;
                        else
                            $jsFiles[$pos][$baseUrl.'/'.$js] = $baseUrl.'/'.$js;
                    }
                }
            }
            if(!empty($package['css']))
            {
                foreach($package['css'] as $css)
                {
                    if(!is_array($css))
                    {
                        $cssFiles[$baseUrl.'/'.$css] = '';
                        continue;
                    }

                    list($cssUrl, $media) = each($css);
                    if(strpos($cssUrl, '//') !== false)
                        $cssFiles[$cssUrl] = $media;
                    else
                        $cssFiles[$baseUrl.'/'.$cssUrl] = $media;
                }
            }
        }
        // merge in place
        if($cssFiles !== array())
        {
            foreach($this->cssFiles as $cssFile => $media)
                $cssFiles[$cssFile] = $media;
            $this->cssFiles = $cssFiles;
        }
        if($jsFiles !== array())
        {
            if(isset($this->scriptFiles[$this->coreScriptPosition]))
            {
                foreach($this->scriptFiles[$this->coreScriptPosition] as $url)
                    $jsFiles[$this->coreScriptPosition][$url] = $url;
            }
            foreach($jsFiles as $pos => $files)
                $this->scriptFiles[$pos] = $files;
        }
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        Yii::app()->setGlobalState($this->cacheStateId, array());

        return true;
    }

    /**
     * Refresh browser cached css/js if modified
     */
    protected function refreshCache()
    {
        $state = !YII_DEBUG ? Yii::app()->getGlobalState($this->cacheStateId, array()) : array();

        if($this->cacheTime > 0 && isset($state['last']) && $state['last'] + $this->cacheTime < time())
            $this->clearCache();
        $state['last'] = time();

        $cssFiles = array();
        foreach($this->cssFiles as $url => $media)
        {
            if(!$this->versionizable($url))
            {
                $cssFiles[$url] = $media;
                continue;
            }

            if(file_exists($this->wwwPath.$url))
            {
            if(!isset($state[$url]))
                $state[$url] = filemtime($this->wwwPath.$url);
            }
            else
                $state[$url] = time();

            $url .= (strpos($url, '?') === false ? '?' : '&v=').date('YmdHis', $state[$url]);
            $cssFiles[$url] = $media;
        }
        $this->cssFiles = $cssFiles;

        $scriptFiles = array();
        foreach($this->scriptFiles as $position => $scripts)
        {
            foreach($scripts as $url => $htmlOptions)
            {
                if(!$this->versionizable($url))
                {
                    $scriptFiles[$position][$url] = $htmlOptions;
                    continue;
                }

                if(file_exists($this->wwwPath.$url))
                {
                if(!isset($state[$url]))
                    $state[$url] = filemtime($this->wwwPath.$url);
                }
                else
                    $state[$url] = time();

                $url .= (strpos($url, '?') === false ? '?' : '&v=').date('YmdHis', $state[$url]);
                $scriptFiles[$position][$url] = $htmlOptions;
            }
            $this->scriptFiles = $scriptFiles;
        }


        Yii::app()->setGlobalState($this->cacheStateId, $state);
    }

    /**
     * Check whether url has to be versioned.
     * Relative URLs allowed.
     * Absolute and those in which version found (-x.x.x.x) are skipped.
     *
     * @param string $url Resource URL
     * @return bool
     */
    protected function versionizable($url)
    {
        if($url{0} !== '/' || strpos($url, '//') !== false || strpos($url, '.') === false)
            return false;

        return !preg_match('/[._-](?:\d+\.?){1,4}/i', $url);
    }
}