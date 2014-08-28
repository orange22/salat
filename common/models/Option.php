<?php
/**
 * This is the model class for table "{{option}}".
 *
 * The followings are the available columns in table '{{option}}':
 *
 * @property integer $id
 * @property string $key
 * @property string $role
 * @property string $value
 * @property string $title
 * @property string $type
 * @property string $config
 * @property integer $serialized
 * @property integer $i18n
 * @property string $group
 * @property string $hint
 * @property integer $sort
 *
 * Methods
 * @method Option cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Option sort($columns = '')
 *
 * The followings are the available model relations:
 * @property AuthItem $authItem
 */
class Option extends BaseActiveRecord
{
    /**
     * Local static cache
     *
     * @var array
     */
    protected static $_cache = array();

    /**
     * Get config value
     *
     * @param string $key Config key
     * @param string $section Specified section
     * @return mixed
     */
    public function cfg($key = null, $section = null)
    {
        if(!$this->config)
            return null;

        $cfg = parse_ini_string($this->config, $section);
        if($key === null && $section === null)
            return $cfg;

        if($section && isset($cfg[$section]))
            $cfg = $cfg[$section];
        if($key && isset($cfg[$key]))
            $cfg = $cfg[$key];

        return $cfg;
    }

    /**
     * Result indexed by column scope
     *
     * @param string $col
     * @return BaseActiveRecord
     */
    public function indexed($col = 'key')
    {
        $this->getDbCriteria()->mergeWith(array(
            'index' => $col,
        ));

        return $this;
    }

    /**
     * Convert value from string/array to array/string
     *
     * @static
     * @param mixed $data Array or string
     * @return mixed $data String or array
     */
    public static function convertValue($data)
    {
        $o = array();
        if(is_array($data))
        {
            foreach($data as $key => $value)
            {
                $o[] = "{$key}={$value}";
            }

            $o = implode("\n", $o);
        }
        else if(is_string($data))
        {
            foreach(explode("\n", $data) as $row)
            {
                list($key, $value) = explode('=', $row);
                $o[trim($key)] = trim($value);
            }
        }

        return $o;
    }

    /**
     * Get groups
     *
     * @static
     * @return array
     */
    public static function getGroups()
    {
        $sql = Yii::app()->db->cache(86400, new TagCacheDependency('Option'))->createCommand();
        $sql->select('GROUP_CONCAT(DISTINCT(`group`) SEPARATOR "\n")')
            ->from('{{option}}');

        return explode("\n", $sql->queryScalar());
    }

    /**
     * Get option value
     *
     * @param string $key Option key
     * @param mixed $default Default if no options found
     * @param string $lang Optional language
     * @return string
     */
    public static function getOpt($key = null, $default = null, $lang = null)
    {
        if(empty(self::$_cache))
        {
            self::$_cache = self::model()->cache()->indexed()->findAll();
        }

        if(!array_key_exists($key, self::$_cache))
            return $default;

        if(self::$_cache[$key]->i18n)
        {
            $lang = !$lang ? Yii::app()->language : $lang;

            return self::$_cache[$key]->value[$lang];
        }

        if(self::$_cache[$key]->serialized)
        {
            return self::convertValue(self::$_cache[$key]->value);
        }

        return self::$_cache[$key]->value;
    }
    
    public static function getDate($key = null, $default = null, $lang = null)
    {
       $months = Array ("1" => "января",
                    "2" => "февраля", 
                    "3" => "марта", 
                    "4" => "апреля", 
                    "5" => "мая", 
                    "6" => "июня", 
                    "7" => "июля", 
                    "8" => "августа", 
                    "9" => "сентября", 
                    "10" => "октября", 
                    "11" => "ноября", 
                    "12" => "декабря",);    
           
       $date=explode('.',self::getOpt($key, $default, $lang));
       
       $timetill=mktime(0, 0, 0, intval($date[1]), $date[0], $date[2]);
       $time=time();
       
        $diff=$timetill-$time; 
        if($diff<1)
        return false;
        // immediately convert to days 
        $temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day 
        
        // days 
        $days=floor($temp);
        $temp=24*($temp-$days); 
        // hours 
        $hours=floor($temp); 
        $temp=60*($temp-$hours); 
        // minutes 
        $minutes=floor($temp); 
        
        $return['days']=$days;
        $return['hours']=$hours;
        $return['minutes']=$minutes;
        /*
        echo "Result: {$days}d {$hours}h {$minutes}m {$seconds}s<br/>\n"; 
                echo "Expected: 7d 0h 0m 0s<br/>\n"; 
               
               echo date("M-d-Y", $time);*/
        
       
       $return['date']=$date[0].' '.$months[intval($date[1])].' '.$date[2];
       
       return $return;
    }

    /**
     * Get roles list
     * Excluded guest and authenticated
     *
     * @return array
     */
    public static function getRoleList()
    {
        return Rights::getAuthItemSelectOptions(CAuthItem::TYPE_ROLE, array(
            'authenticated', 'guest'
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
     * @return Option the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Get options input types
     *
     * @param string $type Specific type
     * @return array
     */
    public static function getTypes($type = null)
    {
        $types = array(
            'textField' => Yii::t('cp', 'Text field'),
            'textArea' => Yii::t('cp', 'Text area'),
            'fileUpload' => Yii::t('cp', 'File'),
            'dropDown' => Yii::t('cp', 'Drop down list')
        );

        if($type && isset($type[$type]))
        {
            return $types[$type];
        }

        return $types;
    }

    public function generateAttributeLabel($name)
    {
        $label = parent::generateAttributeLabel($name);

        if(($pos = strpos($name, '[')) !== false)
        {
            $label = substr($label, 0, $pos);
        }

        return $label;
    }


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{option}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('key', 'required'),
            array('key', 'unique'),
            array('role', 'required', 'on' => 'create'),
            array('serialized, i18n, sort', 'numerical', 'integerOnly' => true),
            array('key, role, group', 'length', 'max' => 64),
            array('title', 'length', 'max' => 256),
            array('type', 'in', 'range' => array_keys(Option::getTypes())),
            array('role, title, value, group, hint, config', 'safe'),

            array('id, key, role, title, value, type, config, serialized, i18n, group, hint, sort', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'authItem' => array(self::BELONGS_TO, 'AuthItem', 'role'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'key' => Yii::t('cp', 'Key'),
            'title' => Yii::t('cp', 'Title'),
            'value' => Yii::t('cp', 'Value'),
            'role' => Yii::t('cp', 'Role'),
            'type' => Yii::t('cp', 'Type'),
            'config' => Yii::t('cp', 'Config'),
            'serialized' => Yii::t('cp', 'Serialized'),
            'i18n' => Yii::t('cp', 'i18n'),
            'group' => Yii::t('cp', 'Group'),
            'hint' => Yii::t('cp', 'Hint'),
            'sort' => Yii::t('cp', 'Sort'),
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
        $criteria->compare('t.key', $this->key, true);
        $criteria->compare('t.role', $this->role, true);
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.value', $this->value, true);
        $criteria->compare('t.type', $this->type, true);
        $criteria->compare('t.config', $this->config, true);
        $criteria->compare('t.serialized', $this->serialized);
        $criteria->compare('t.i18n', $this->i18n);
        $criteria->compare('t.group', $this->group, true);
        $criteria->compare('t.hint', $this->hint, true);
        $criteria->compare('t.sort', $this->sort);

        if(!Yii::app()->user->getIsSuperUser())
        {
            $criteria->addInCondition('t.role', Yii::app()->user->getRoles());
        }

        return parent::searchInit($criteria);
    }

    /**
     * After find
     *
     * @return void
     */
    protected function afterFind()
    {
        parent::afterFind();

        if($this->serialized)
            $this->setAttribute('value', $this->convertValue(unserialize($this->value)));

        if(!$this->serialized && $this->i18n)
            $this->setAttribute('value', unserialize($this->value));
    }

    protected function beforeSave()
    {
        if($this->serialized)
            $this->setAttribute('value', serialize($this->convertValue($this->value)));

        if(!$this->serialized && $this->i18n)
            $this->setAttribute('value', serialize($this->value));

        return parent::beforeSave();
    }

    protected function beforeValidate()
    {
        if(!$this->getIsNewRecord())
        {
            if(!Yii::app()->user->checkAccess('Option.Create'))
            {
                /** @var $original Option */
                $original = $this->findByPk($this->id);
                $this->setAttribute('key', $original->key);
                $this->setAttribute('role', $original->role);
                $this->setAttribute('type', $original->type);
                $this->setAttribute('config', $original->config);
                $this->setAttribute('title', $original->title);
                $this->setAttribute('serialized', $original->serialized);
                $this->setAttribute('i18n', $original->i18n);
                $this->setAttribute('group', $original->group);
                $this->setAttribute('hint', $original->hint);
                $this->setAttribute('sort', $original->sort);
            }
        }

        return parent::beforeValidate();
    }
}