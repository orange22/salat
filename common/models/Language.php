<?php
/**
 * This is the model class for table "{{language}}".
 *
 * The followings are the available columns in table '{{language}}':
 *
 * @property string $id
 * @property string $locale
 * @property string $title
 * @property string $title_alt
 * @property integer $public
 * @property integer $status
 * @property integer $sort
 *
 * @method Language active
 * @method Language cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Language indexed($col = 'language_id')
 * @method Language sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Literal[] $literals
 * @property Media[] $medias
 * @property Post[] $posts
 * @property Quote[] $quotes
 * @property Share[] $shares
 * @property Slider[] $sliders
 * @property User[] $users
 */
class Language extends BaseActiveRecord
{
    /**
     * Default lang
     *
     * @static
     * @var string
     */
    public static $default = '';

    /**
     * Active lang
     *
     * @static
     * @var string
     */
    public static $active = '';

    /**
     * Languages list data
     *
     * @var array
     */
    public static $list = array();

    /**
     * All languages
     *
     * @var array
     */
    protected static $_languages = array();

    /**
     * Delete language related content
     */
    public function deleteContent()
    {
        foreach($this->relations() as $params)
        {
            /** @var $model LangActiveRecord */
            $model = call_user_func(array($params[1], 'model'));
            $model->resetScope();
            $model->deleteAllByAttributes(array(
                'language_id' => $this->id
            ));
        }
    }

    /**
     * Get active language code
     *
     * @static
     * @return string
     */
    public static function getActive()
    {
        if(self::$active)
            return self::$active;

        $o = Yii::app()->request->getParam('lang');
        $o = !$o && isset(Yii::app()->request->cookies['lang']) ? Yii::app()->request->cookies['lang']->value : $o;

        if($o && !Language::languages($o))
            $o = null;
        $o = !$o ? Language::getDefault() : $o;

        self::$active = $o;

        if(!Yii::app()->request->isAjaxRequest)
        {
            unset(Yii::app()->request->cookies['lang']);
            $cookie = new CHttpCookie('lang', self::$active);
            $cookie->expire = time() + 31104000; // 1 year
            Yii::app()->request->cookies['lang'] = $cookie;
        }

        return $o;
    }

    /**
     * Get default language code
     *
     * @static
     * @return string
     */
    public static function getDefault()
    {
        if(!self::$default)
        {
            $cur = current(Language::languages());
            self::$default = $cur ? $cur->id : 'en';
        }

        return self::$default;
    }

    /**
     * Get languages list
     *
     * @static
     * @return array
     */
    public static function getList()
    {
        if(empty(self::$list))
            self::$list = CHtml::listData(Language::languages(), 'id', 'title');

        return self::$list;
    }

    /**
     * Get languages list for menu
     *
     * @static
     * @return array
     */
    public static function getMenuList()
    {
        $o = array();
        $act = self::getActive();

        foreach(Language::languages() as $item)
        {
            $public = ($item['public'] || !Yii::app()->user->getIsGuest());
            if(!$public)
                continue;

            if($item['id'] == $act)
                continue;

            $tmp = array(
                'label' => $item->title_alt ? $item->title_alt : $item->title,
                'url' => self::url('', array('lang' => $item['id'])),
                'active' => false,
                'id' => $item['id'],
            );

            $o[] = $tmp;
        }

        return current($o);
    }

    /**
     * Get language title
     *
     * @static
     * @param string $code Language code
     * @param bool $useAlt Skip alternative title
     * @return string Language title
     */
    public static function getTitle($code, $useAlt = true)
    {
        $lang = self::languages($code);
        $o = $useAlt && $lang['title_alt'] ? $lang['title_alt'] : $lang['title'];

        return $o;
    }

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className
     * @return Language the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{language}}';
    }

    /**
     * Refresh cache
     */
    public function refreshCache()
    {
        Yii::app()->setGlobalState('cache.languages', time());

        parent::refreshCache();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id', 'unique'),
            array('id, title', 'required'),
            array('public, status, sort', 'numerical', 'integerOnly' => true),
            array('id', 'length', 'max' => 2),
            array('title, title_alt', 'length', 'max' => 64),
            array('locale', 'safe'),

            array('id, locale, title, title_alt, status, sort', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'literals' => array(self::HAS_MANY, 'Literal', 'language_id'),
            'medias' => array(self::HAS_MANY, 'Media', 'language_id'),
            'posts' => array(self::HAS_MANY, 'Post', 'language_id'),
            'quotes' => array(self::HAS_MANY, 'Quote', 'language_id'),
            'shares' => array(self::HAS_MANY, 'Share', 'language_id'),
            'sliders' => array(self::HAS_MANY, 'Slider', 'language_id'),
            'users' => array(self::HAS_MANY, 'User', 'language_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'locale' => Yii::t('backend', 'Locale'),
            'title' => Yii::t('backend', 'Title'),
            'title_alt' => Yii::t('backend', 'Alternative Title'),
            'public' => Yii::t('backend', 'Public'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
        );
    }

    /**
     * Scopes
     *
     * @return array
     */
    public function scopes()
    {
        return array(
            'active' => array(
                'condition' => 'status = 1',
            ),
            'public' => array(
                'condition' => 'public = 1',
            ),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.locale', $this->locale, true);
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.title_alt', $this->title_alt, true);
        $criteria->compare('t.public', $this->public);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.sort', $this->sort);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
            'criteria' => $criteria,
        ));
    }

    /**
     * Generate i18n route
     *
     * @static
     * @param string $route Route
     * @param array $vars Additional prams
     * @return string
     */
    public static function url($route = null, $vars = array())
    {
        $route = !$route ? Yii::app()->request->getQuery('r') : $route;
        if(!$route && ($ctrl = Yii::app()->getController()))
            $route = $ctrl->getRoute();

        foreach(Yii::app()->urlManager->vars as $param)
        {
            if(!isset($vars[$param]) && ($requestValue = Yii::app()->request->getQuery($param, null)))
                $vars[$param] = $requestValue;
        }

        return Yii::app()->createUrl($route, $vars);
    }

    /**
     * Check whether specified language exists
     *
     * @param string $lang Language string
     * @return bool
     */
    public static function exist($lang)
    {
        return (self::languages($lang) !== null);
    }

    /**
     * Get all available languages
     *
     * @static
     * @param string $lang Specific language
     * @return array|string
     */
    public static function languages($lang = null)
    {
        if(!self::$_languages)
        {
            $cdep = new CGlobalStateCacheDependency('cache.languages');
            self::$_languages = Language::model()
                ->cache(86400, $cdep)
                ->indexed('id')
                ->active()
                ->sort()
                ->findAll();
        }

        $lang = (string)$lang;
        if($lang)
        {
            if(isset(self::$_languages[$lang]))
            {
                return self::$_languages[$lang];
            }

            return null;
        }

        return self::$_languages;
    }
}