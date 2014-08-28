<?php
/**
 * Entity
 * Post relationships
 *
 * @property BaseActiveRecord owner
 */
class Entity extends CActiveRecordBehavior
{
    /**
     * Post entities
     *
     * @var array
     */
    protected static $entities = array(
      	1 => 'dish'
	    
	   /*
        1 => 'dish',
              2 => 'event',
              3 => 'special',
              4 => 'service',
              5 => 'teacher',
              6 => 'faculty',
              7 => 'price',
              8 => 'career',
              9 => 'press',
              10 => 'department',
              11 => 'product',
              12 => 'schedule',
              13 => 'author',
              14 => 'gallery',
              20 => 'archive',
      
              15 => 'course',
              16 => 'genre',
              17 => 'speciality',
      
              18 => 'audio',
              19 => 'pressRoom',*/
      

    );

    /**
     * Equivalent entities
     *
     * @var array
     */
    protected static $equal = array(
        'work' => 'gallery'
    );

    /**
     * Get entity ID
     *
     * @return int|null Entity ID or null if not found
     */
    public function id()
    {
        $strId = lcfirst(get_class($this->owner));
        if($key = array_search($strId, self::$entities))
            return $key;

        if(isset(self::$equal[$strId]))
            return array_search(self::$equal[$strId], self::$entities);

        return null;
    }

    /**
     * Linkable entities
     *
     * @return array
     */
    public function linkable()
    {
        $className = $this->owner->classId(true);
        if(in_array($className, array('blog', 'event', 'gallery')))
            return array(1, 2, 14);

        if($className === 'teacher')
            return array(5, 6);

        return $this->id();
    }

    /**
     * Get entity
     *
     * @static
     * @param string|int $data Entity number or name
     * @return mixed Entity numeric or string repr, according to $data is number/string
     */
    public static function get($data)
    {
        if(is_numeric($data) && isset(self::$entities[$data]))
            return self::$entities[$data];

        $data = lcfirst($data);
        if($key = array_search($data, self::$entities))
            return $key;

        return null;
    }

    /**
     * Get entity keys
     *
     * @static
     * @return array
     */
    public static function keys()
    {
        return array_keys(self::$entities);
    }

    /**
     * Get group entities
     *
     * @static
     * @param string $data Group name
     * @return array|null
     */
    public static function group($data)
    {
        switch($data)
        {
            case 'post':
                return array_merge(array_slice(self::$entities, 0, 14, true), array('20'));
            break;
            case 'literal':
                return array_slice(self::$entities, 14, 3, true);
            break;
            case 'media':
                return array_slice(self::$entities, 17, 2, true);
            break;
            default:
                return null;
        }
    }

    /**
     * List of entities
     *
     * @return array
     */
    public static function listData()
    {
        return self::$entities;
    }

    /**
     * Post entities showed on index
     *
     * @static
     * @return array
     */
    public static function indexPostEntities()
    {
        return array(1, 2, 14);
    }

    /**
     * Posts tiled output
     *
     * @static
     * @return array
     */
    public static function tiled()
    {
        return array(1, 2, 5, 8, 9, 11, 13, 14, 19, 20);
    }

    /**
     * Entities with begin date
     *
     * @return array
     */
    public static function withDate()
    {
        return array(1, 2, 9, 14, 20);
    }

    /**
     * Entities with comments
     *
     * @return array
     */
    public static function commentable()
    {
        return array(1, 2);
    }
}
