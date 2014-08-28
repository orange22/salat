<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
abstract class FrontController extends CController
{
    /**
     * Site-wide data
     *
     * @var array
     */
    public $data = array();
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
	public $cart = array();
	public $og_title;
	public $og_description;
	public $og_image;
	
	public $seo_title;
	public $seo_description;
	public $seo_image;
	public $seo_keywords;
	public $dishtype_id;
	public $firstcategory_id;
	public $tdishes;
    public $povar_newyear;
    public $current_popup;
    public $display_popup;
    public $display_closed_popup;
    /**
     * Model name
     *
     * @var string
     */
    protected $modelName = null;
	const PAGE_SIZE = 10;
    public function init()
    {
        parent::init();
	    Yii::import('ext.Paging');
    	$this->og_title=$this->seo_title=Option::getOpt('seotitle');
		$this->og_description=$this->seo_description=Option::getOpt('seodescription');
		$this->og_image=$this->seo_image=Option::getOpt('seoimage');
		$this->seo_keywords=Option::getOpt('seokeywords');
		$this->cart = $this->getCart();

        /*if(Yii::app()->detectMobileBrowser->showMobile){
            $this->povar_newyear=1;
            $this->povar_newyear = Yii::app()->request->cookies['povar_newyear'];
            if(!$this->povar_newyear){
                $cookie = new CHttpCookie('povar_newyear', 1);
                $cookie->expire = time()+60*60*24*3;
                Yii::app()->request->cookies['povar_newyear'] = $cookie;
            }
        }else{*/
            //$this->povar_newyear = Yii::app()->session['povar_newyear2'];
            //if(!$this->povar_newyear)
                //Yii::app()->session['povar_newyear2'] = 1;
       // }
        //if($_SERVER['REMOTE_ADDR']=='91.209.51.157'){
            //echo Yii::app()->request->cookies['current_popup'];
        /*$this->current_popup=0;
        $this->current_popup = Yii::app()->request->cookies['current_popup'];
        if((string)$this->current_popup<3 && !Yii::app()->detectMobileBrowser->showMobile){
            if(!Yii::app()->session['current_popup_sess']){
                Yii::app()->request->cookies['current_popup'] = new CHttpCookie('current_popup', (string)$this->current_popup+1);
                Yii::app()->session['current_popup_sess']=1;
                $this->display_popup=1;
            }
        }*/
            //unset(Yii::app()->session['current_popup_sess']);
            //unset(Yii::app()->request->cookies['current_popup']);

        //}
        if(!Yii::app()->session['closed_popup']){
            Yii::app()->session['closed_popup']=1;
            $this->display_closed_popup=1;
        }else{
            $this->display_closed_popup=0;
        }


        //unset(Yii::app()->request->cookies['povar_newyear']);
        $this->tdishes=Dishtype::model()->getTopDishes();

        //$this->tdishes=Dishtype::model()->sort('t.sort ASC')->active()->with(array('dishtypeimage','dishes'=>array('select'=>false,'joinType'=>'INNER JOIN')))->limit(6,0)->findAll();

        if(isset($this->tdishes['types'][0])){
			$this->firstcategory_id=$this->tdishes['types'][0]['id'];
		}	
        $this->setModelName(ucfirst($this->getId()));
    }

    /**
     * Check is AJAX request
     * _GET[ajax] OR XMLHttpRequest
     *
     * @return bool
     */
    public static function intMorphy ( $int, $im, $rd, $rdm )
	{
		$a = $int % 10;
		$b = $int % 100;
		
		switch(true) {
		case($a == 0 || $a >= 5 || ($b >= 10 && $b <= 20)):
		$result = $rdm;
		break;
		case($a == 1):
		$result = $im;
		break;
		case($a >= 2 && $a <= 4):
		$result = $rd;
		break;
		}
		
		return $result;
	}
    public function isAjax()
    {
        if(isset($_GET['ajax']) || request()->isAjaxRequest)
            return true;

        return false;
    }
	
	public function	genRandomString($length=5, $chars='', $type=array()) {
	    //initialize the characters
	    $alphaSmall = 'abcdefghijklmnopqrstuvwxyz';
	    $alphaBig = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $num = '0123456789';
	    $othr = '`~!@#$%^&*()/*-+_=[{}]|;:",<>.\/?' . "'";
	
	    $characters = "";
	    $string = '';  
	    //defaults the array values if not set
	    isset($type['alphaSmall'])  ? $type['alphaSmall']: $type['alphaSmall'] = true;  //alphaSmall - default true  
	    isset($type['alphaBig'])    ? $type['alphaBig']: $type['alphaBig'] = false;      //alphaBig - default true
	    isset($type['num'])         ? $type['num']: $type['num'] = true;                //num - default true
	    isset($type['othr'])        ? $type['othr']: $type['othr'] = false;             //othr - default false 
	    isset($type['duplicate'])   ? $type['duplicate']: $type['duplicate'] = true;    //duplicate - default true     
	    
	    if (strlen(trim($chars)) == 0) { 
	        $type['alphaSmall'] ? $characters .= $alphaSmall : $characters = $characters;
	        $type['alphaBig'] ? $characters .= $alphaBig : $characters = $characters;
	        $type['num'] ? $characters .= $num : $characters = $characters;
	        $type['othr'] ? $characters .= $othr : $characters = $characters;        
	    }
	    else
	        $characters = str_replace(' ', '', $chars);
	      
	    if($type['duplicate'])
	        for (; $length > 0 && strlen($characters) > 0; $length--) {
	            $ctr = mt_rand(0, (strlen($characters)) - 1);
	            $string .= $characters[$ctr];
	        }
	    else
	        $string = substr (str_shuffle($characters), 0, $length);
	   
	    return $string;
	}
	
	public function youtube_id_from_url($url) {
    $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:
		www\.
		| m\.
		| www\.m\.
		)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
		  (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
		  | /watch\?feature=player_embedded\&v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        %x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if (false !== $result) {
        return $matches[1];
    }
    return false;
	}
	public function vimeo_id_from_url($url){
		if(!$url)
		return;
		preg_match('/vimeo.com\/(\d+)?/', trim($url), $match);
		if($match['1']>0)
		return $match['1'];
	}
	public function renderTopDishes(){
		return $this->renderPartial('/layouts/inc/topdish',array('tdishes'=>$this->tdishes),true);
	}
	public function renderWidgets($id=null){
		$where=null;	
		if($id>0)
		$where=' AND t.id!='.$id;
		$widgets=Page::model()->sort()->active()->findAll('t.widget=1'.$where);
		return $this->renderPartial('/layouts/inc/widgets',array('widgets'=>$widgets),true);
	}
	public function renderPartners(){
		$partners=Partner::model()->sort()->active()->findAll();
		return $this->renderPartial('/layouts/inc/partners',array('partners'=>$partners),true);
	}
	public function renderShare($url=null,$title=null){
		$url=str_replace('http://', '', $url);
		$url=$_SERVER['HTTP_HOST'].$url;
		//$partners=Partner::model()->findAll();
		return $this->renderPartial('/layouts/inc/share',array('shares'=>0,'title'=>urlencode($title),'url'=>$url),true);
	}
    /**
     * Create absolute return url to page
     *
     * @param string $type URL type [post, create, handled]
     * @param string $route Route
     * @param array $params Additional params
     * @param string $schema Host info schema [http, https]
     * @return string Base64 encoded absolute url
     * @throws CHttpException
     */
    public function returnUrl($type = 'post', $route, $params = array(), $schema = '')
    {
        $method = $type.'Url';
        if(!method_exists($this, $method))
            throw new CHttpException(500, Yii::t('frontend', 'Undefined method {method}', array('{method}' => $method)));

        if(is_object($params))
            $params = $this->extractUrlData($params);
        $url = $this->{$method}($route, $params);
        if(strpos($url, 'http') === false)
            $url = Yii::app()->getRequest()->getHostInfo($schema).$url;

        return base64_encode($url);
    }
	public function actionMessage($title=null,$message=null)
    {
    	$this->render('/site/message',array('title'=>$title,'message'=>$message));
	}
    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if($route !== '/')
        {
            if(!isset($params['page']) && isset($_GET['page']))
                $params['page'] = request()->getQuery('page');
            if(!isset($params['site']) && isset($params['page']))
                $params['site'] = PageFront::topPageOf($params['page']);

            if(strpos($route, '/') === false)
                $route .= '/index';
        }
        else
            $route = 'site/index';

        return parent::createUrl($route, $params, $ampersand);
    }

    /**
     * Create URL looking for specific page handler
     *
     * @param string $route
     * @param array $params
     * @param string $ampersand
     * @return string
     */
    public function handledUrl($route, $params = array(), $ampersand = '&')
    {
        if($route !== '/')
        {
            $pid = PageFront::pidOfHandler($route);
            if(!$pid)
            {
                $data = Page::splitHandler($route);
                $pid = PageFront::pidOfHandler($data['controller']);
            }

            if($pid)
                $params['page'] = $pid;
        }

        return $this->createUrl($route, $params, $ampersand);
    }

    public function render($view, $data = null, $return = false, $processOutput = true)
    {
        if(app()->viewRenderer)
            return parent::renderPartial($view, $data, $return, $processOutput);

        return parent::render($view, $data, $return);
    }

    /**
     * Get model via Model::model()
     *
     * @return BaseActiveRecord
     */
    public function getModel()
    {
        return call_user_func(array($this->getModelName(), 'model'));
    }

    public function getModelName()
    {
        return $this->modelName;
    }

    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * Get new model
     *
     * @param string $scenario
     * @throws CHttpException
     * @return BaseActiveRecord
     */
    public function getNewModel($scenario = 'insert')
    {
        if(!$this->modelName)
            throw new CHttpException(500, Yii::t('front', 'No model specified'));

        if(!class_exists($this->modelName))
            throw new CHttpException(500, Yii::t('front', 'Model "{model}" not found', array('{model}' => $this->modelName)));

        return new $this->modelName($scenario);
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Throw error 404
     *
     * @throws CHttpException
     */
    public function e404()
    {
        throw new CHttpException(404, Yii::t('frontend', 'Page not found.'));
    }

    /**
     * Login using social
     */
    public function actionLogin()
    {
        $backUrl = base64_decode(request()->getQuery('returnUrl'));
        $this->redirect($backUrl);
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        $this->redirect(user()->getState('lastLocation', Yii::app()->homeUrl));
    }

    /**
     * Send headers with json_encoded data and end app
     *
     * @param mixed $data
     */
    public function ajaxResponse($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        app()->end();
    }
	protected function sendJsonResponse($data = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        if($data !== null)
            echo json_encode($data);
        Yii::app()->end();
    }
    /**
     * Check IE browser version
     *
     * @param int $version IE version
     * @return bool
     */
    public function isBadIe($version = 7)
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('#MSIE[/ ]+(\d+)#i', $agent, $m) && !preg_match('/Opera/i', $agent))
        {
            if((int)$m[1] <= $version)
                return true;
        }

        return false;
    }

    protected function afterAction($action)
    {
        user()->setState('lastLocation', request()->getUrl());
        parent::afterAction($action);
    }

    /**
     * Build page vars
     *
     * @param array $custom Specific vars
     * @return array
     */
    /*
    protected function vars($custom = array())
        {
            $default = array(
                'site' => !array_key_exists('site', $custom) ? PageFront::data(request()->getQuery('site')) : null,
                'page' => !array_key_exists('page', $custom) ? PageFront::data(request()->getQuery('page')) : null,
            );
    
            $vars = CMap::mergeArray($default, $custom);
            $this->prepareData($vars);
    
            if(!$this->isAjax())
            {
                $this->prepareMenu();
                $metaData = $this->resolveMetaDataSource($vars);
                if($metaData)
                {
                    cs()->registerMetaTag($metaData['keywords'], 'keywords');
                    cs()->registerMetaTag($metaData['description'], 'description');
                }
    
                if($metaData['title'])
                    $this->setPageTitle($metaData['title']);
                else
                    $this->setPageTitle(PageFront::buildPageTitle($vars));
            }
    
            return $vars;
        }*/
    

    /**
     * Resolve item to fetch seo from
     *
     * @param array $vars
     * @return array
     */
    protected function resolveMetaDataSource(&$vars)
    {
        if($this->action->getId() === 'view')
        {
            if(!isset($vars['data']))
                return null;

            if(is_object($vars['data']))
                return $vars['data']->seo ? $vars['data']->seo->fetchMetaData() : null;

            if(is_array($vars['data']) && isset($vars['data']['pid'], $vars['data']['entity']))
                return Seo::model()->fetchMeta($vars['data']);

            return null;
        }

        if(!$this->isIndex())
        {
            return Seo::model()->fetchMeta(array(
                'entity' => 'page',
                'pid' => $vars['page']['pid'],
                'language_id' => app()->language
            ));
        }

        return Seo::model()->fetchMeta(array(
            'entity' => 'page',
            'pid' => PageFront::rootPid(),
            'language_id' => app()->language
        ));
    }

    /**
     * Prepare site data
     *
     * @param array $params
     */
    protected function prepareData($params = array())
    {
        $this->data['sites'] = PageFront::sites();
        $this->data['pageCode'] = 'page-index';
        if(!$this->isIndex())
        {
            $pageCode = array('page');
            if(isset($params['site']))
            {
                $this->data['site'] = $params['site'];
                $pageCode[] = $params['site']['code'];
            }
            if(isset($params['page']))
            {
                $this->data['page'] = $params['page'];
                if($params['page']['entity'])
                    $pageCode[] = Entity::get($params['page']['entity']);
                else
                    $pageCode[] = $params['page']['alias'];
            }

            $this->data['pageCode'] = implode('-', $pageCode);
        }

        if(!$this->isAjax())
        {
            $this->data = CMap::mergeArray(array_filter(array(
                'ga' => Option::getOpt('site.ga'),
                'phone' => !isset($params['pagePhone']) ? Option::getOpt('site.phone') : $params['pagePhone'],
                'address' => Option::getOpt('site.address'),
                'copyright' => Option::getOpt('site.copyright'),
                'social' => array_filter(array(
                    'fb' => Option::getOpt('site.facebook'),
                    'vk' => Option::getOpt('site.vkontakte'),
                    'tw' => Option::getOpt('site.twitter'),
                ))
            )), $this->data);
        }
    }

    /**
     * Prepare menu data
     */
    protected function prepareMenu()
    {
        $this->menu['main'] = !isset($this->menu['main'])
            ? PageFront::menu(!$this->isIndex() ? request()->getQuery('site') : null)
            : $this->menu['main'];

        if(!$this->isIndex())
        {
            $parent = PageFront::closestNavPageOf(request()->getQuery('page'));
            $this->menu['secondary'] = !isset($this->menu['secondary'])
                ? PageFront::menu($parent['pid'])
                : $this->menu['secondary'];
        }

        $this->menu['languages'] = !isset($this->menu['languages'])
            ? Language::getMenuList()
            : $this->menu['languages'];
    }
	/**
     * Get cart with product model
     *
     * @return Cart
     */
    protected function getCart()
    {
        return Cart::getCart(array('model' => Dish::model()));
    }
}