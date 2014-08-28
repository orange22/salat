<?php
/**
 * Base class for all active record models
 *
 * @property ArrayBehavior $array
 */
abstract class BaseActiveRecord extends CActiveRecord
{
    function GetInTranslit($string) {
		$replace=array(
		    ","=>"",
		    "."=>"",
		    "-"=>"_",
		    " "=>"_",
		    "+"=>"",
			"'"=>"",
			"`"=>"",
            "?"=>"",
			"а"=>"a","А"=>"a",
			"б"=>"b","Б"=>"b",
			"в"=>"v","В"=>"v",
			"г"=>"g","Г"=>"g",
			"д"=>"d","Д"=>"d",
			"е"=>"e","Е"=>"e",
			"ж"=>"zh","Ж"=>"zh",
			"з"=>"z","З"=>"z",
			"и"=>"i","И"=>"i",
			"й"=>"y","Й"=>"y",
			"к"=>"k","К"=>"k",
			"л"=>"l","Л"=>"l",
			"м"=>"m","М"=>"m",
			"н"=>"n","Н"=>"n",
			"о"=>"o","О"=>"o",
			"п"=>"p","П"=>"p",
			"р"=>"r","Р"=>"r",
			"с"=>"s","С"=>"s",
			"т"=>"t","Т"=>"t",
			"у"=>"u","У"=>"u",
			"ф"=>"f","Ф"=>"f",
			"х"=>"h","Х"=>"h",
			"ц"=>"c","Ц"=>"c",
			"ч"=>"ch","Ч"=>"ch",
			"ш"=>"sh","Ш"=>"sh",
			"щ"=>"sch","Щ"=>"sch",
			"ъ"=>"","Ъ"=>"",
			"ы"=>"y","Ы"=>"y",
			"ь"=>"","Ь"=>"",
			"э"=>"e","Э"=>"e",
			"ю"=>"yu","Ю"=>"yu",
			"я"=>"ya","Я"=>"ya",
			"і"=>"i","І"=>"i",
			"ї"=>"yi","Ї"=>"yi",
			"є"=>"e","Є"=>"e"
		);
		return $str=str_replace("__", "_", iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace)));
	}
    public function getUrl($url=null)
    {
        $controller=get_class($this);
        $controller[0]=strtolower($controller[0]);
        if(!$url)
            $url=$controller;
        $params=array('id'=>$this->id);
        // add the title parameter to the URL
        if($this->hasAttribute('title'))
            $params['title']=self::GetInTranslit($this->title);
        return Yii::app()->urlManager->createUrl($url.'/view', $params);
    } 
	/**
     * Get ID attribute name
     *
     * @return string
     */
    public function getIdAttr()
    {
        return 'id';
    }

    /**
     * Get model display title
     *
     * @return string
     */
    public function getDisplayTitle()
    {
        return $this->title;
    }

    /**
     * Get title attribute name
     *
     * @return string
     */
    public function getTitleAttr()
    {
        return 'title';
    }

    /**
     * List of items
     *
     * @param array $filterKeys Select only specified keys
     * @return array
     */
    public function listData($filterKeys = array(),$sort='title')
    {
        $data = $this->sort($sort);

        if(isset($this->status))
            $data = $data->active();

        if($filterKeys)
            $data = $data->findAllByPk(array('id' => $filterKeys));
        else
            $data = $data->findAll();
        $this->resetScope();

        return CHtml::listData((array)$data, $this->getIdAttr(), $this->getTitleAttr());
    }

    /**
     * Get all active keys
     *
     * @param array $filterKeys Select only specified keys
     * @return array
     */
    public function fetchKeys($filterKeys = array())
    {
        $where = array('and', 'status = 1', '1=1');
        if($filterKeys)
            $where[2] = array('in', 'id', $filterKeys);

        $data = app()->db
            ->cache(param('cacheDuration'), new TagCacheDependency($this->classId()))
            ->createCommand()
            ->select('GROUP_CONCAT(id)')
            ->from($this->tableName())
            ->where($where)
            ->queryScalar();

        return explode(',', $data);
    }

    /**
     * Get class ID
     *
     * @param bool $lower
     * @return string Class name
     */
    public function classId($lower = false)
    {
        $className = get_class($this);
        if($lower)
            $className = strtolower($className);

        return $className;
    }
	
	/**
     * Convert attribute array of errors to string
     * Rid of multidimensional errors array
     *
     * @return array Array of attributes errors [label: error]
     */
    public function stringifyAttributeErrors()
    {
        $o = array();
        foreach($this->getErrors() as $attr => $errors)
        {
            $label = $this->getAttributeLabel($attr);
            if(count($errors) > 1)
                $buff = '<ul><li>'.implode('</li><li>', $errors).'</li></ul>';
            else
                $buff = is_array($errors) ? $errors[0] : $errors;

            $o[$label] = $buff;
        }

        return $o;
    }

    /**
     * Restore model grid state
     */
    public function restoreGridState()
    {
        $className = ucfirst(get_class($this));
        if(isset(Yii::app()->request->cookies['gridState:'.$className]))
        {
            $state = unserialize(base64_decode(Yii::app()->request->cookies['gridState:'.$className]->value));
            if(isset($state['attr']))
            {
                if(isset($_GET[$className]))
                    $state['attr'] = CMap::mergeArray($state['attr'], $_GET[$className]);
                $this->attributes = CMap::mergeArray($this->attributes, array_filter($state['attr']));
            }

            if($state['sort'] && !isset($_GET[$className.'_sort']))
                $_GET[$className.'_sort'] = $state['sort'];

            if($state['page'] && !isset($_GET[$className.'_page']) && !isset($_GET['ajax']))
                $_GET[$className.'_page'] = $state['page'];
        }
    }

    /**
     * Cache data using class name as key
     *
     * @param int $duration
     * @param null $dependency
     * @param int $queryCount
     * @return CActiveRecord|BaseActiveRecord
     */
    public function cache($duration = null, $dependency = null, $queryCount = 1)
    {
        if(!$dependency)
            $dependency = new TagCacheDependency(get_class($this));

        if(!$duration)
            $duration = isset(Yii::app()->params['cacheDuration']) ? Yii::app()->params['cacheDuration'] : 3600;

        return parent::cache($duration, $dependency, $queryCount);
    }

    /**
     * Cache related to current model
     *
     * @return array
     */
    public function relatedCache()
    {
        return array();
    }

    /**
     * Refresh model cache with related models cache
     *
     * @param array $other Other tags to clean
     */
    public function refreshCache($other = array())
    {
        if(!Yii::app()->cache)
            return;

        $time = microtime(true);
        Yii::app()->cache->set(get_class($this), $time, 0);

        foreach((array)$this->relatedCache() as $item)
            Yii::app()->cache->set($item, $time, 0);
        if($other)
        {
            foreach((array)$other as $item)
                Yii::app()->cache->set($item, $time, 0);
        }
    }

    public function getAttributeLabel($attribute)
    {
        if(strpos($attribute, '[') !== false)
            CHtml::resolveName($this, $attribute);

        if(($pos = strpos($attribute, '[')) !== false)
            $attribute = substr($attribute, 0, $pos);

        return parent::getAttributeLabel($attribute);
    }

    /**
     * Fields select scope
     *
     * @param string $columns
     * @return BaseActiveRecord
     */
    public function select($columns = '*')
    {
        $this->getDbCriteria()->mergeWith(array(
            'select' => $columns,
        ));

        return $this;
    }

    /**
     * Result indexed by column scope
     *
     * @param string $col
     * @return BaseActiveRecord
     */
    public function indexed($col = 'id')
    {
        $this->getDbCriteria()->mergeWith(array(
            'index' => $col,
        ));

        return $this;
    }

    /**
     * Limit scope
     *
     * @param int $limit
     * @param int $offset
     * @return BaseActiveRecord
     */
    public function limit($limit, $offset = 0)
    {
        $this->getDbCriteria()->mergeWith(array(
            'limit'  => $limit,
            'offset' => $offset,
        ));

        return $this;
    }

    /**
     * Sorting scope
     *
     * @param string $columns
     * @return BaseActiveRecord
     */
    public function sort($columns = '')
    {
        $columns = $columns ? $columns : $this->tableAlias.'.sort';
        $this->getDbCriteria()->mergeWith(array(
            'order' => $columns,
        ));

        return $this;
    }

    /**
     * Scopes
     *
     * @return array
     */
    public function scopes()
    {
        return array(
            'active'  => array(
                'condition' => $this->tableAlias.'.status = 1',
            ),
        );
    }

    /**
     * Update junction table
     *
     * @param array $data Array with insert,delete keys which are arrays of ID's
     * @param string $table Table name to update
     * @param string $column Column name to update
     * @throws CException
     * @throws CDbException
     * @return int Number of inserted+deleted rows
     */
    public function updateJunction($data, $table, $column)
    {
        $idColumn = (!$this->hasAttribute('pid')) ? 'id' : 'pid';
        $id = $this->$idColumn;

        if(!$id)
            throw new CException(Yii::t('backend', 'Junction table ID not defined'));

        $objField = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_\1', get_class($this)).'_'.$idColumn);

        $o = 0;
        $sql = app()->db->createCommand();
        $ta = app()->db->beginTransaction();

        try
        {
            if(!empty($data['delete']))
            {
                $o += $sql->delete($table,
                    array('and', $objField.' = :o_id', array('IN', $column, $data['delete'])),
                    array(':o_id' => $id)
                );
            }

            $sql->reset();

            foreach($data['insert'] as $item)
            {
                $o += $sql->insert($table, array(
                    $objField => $id,
                    $column => $item
                ));
            }

            $ta->commit();
        }
        catch(CDbException $e)
        {
            $ta->rollback();
            throw $e;
        }

        return $o;
    }

    protected function afterSave()
    {
        $this->refreshCache();

        parent::afterSave();
    }

    protected function afterDelete()
    {
        $this->refreshCache();

        parent::afterDelete();
    }

    /**
     * Search initialization
     *
     * @param CDbCriteria $criteria
     * @return CActiveDataProvider
     */
    protected function searchInit($criteria = null)
    {
        if(!$criteria)
            $criteria = new CDbCriteria;

        if($this->hasAttribute('site_pid') && ($sites = user()->hasItemAccess('site')) !== null)
        {
            $criteria->addInCondition('site_pid', $sites);
        }

        $this->storeGridState();

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
            'criteria' => $criteria,
        ));
    }

    /**
     * Store model grid state
     */
    protected function storeGridState()
    {
        $className = ucfirst(get_class($this));
        $state = base64_encode(serialize(array(
            'attr' => $this->getAttributes(),
            'sort' => !isset($_GET[$className.'_sort']) ? null : $_GET[$className.'_sort'],
            'page' => !isset($_GET[$className.'_page']) ? null : $_GET[$className.'_page'],
        )));
        request()->cookies['gridState:'.$className] = new CHttpCookie('gridState:'.$className, $state, array(
            'expire' => time() + 600,
            'httpOnly' => true,
        ));
    }
}
