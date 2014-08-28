<?php
/**
 * TagCacheDependency class.
 *
 * TagCacheDependency represents a dependency based on a autoincrement(timestamp) of tags
 *
 * @author Roman <astronin@gmail.com>
 * @since 1.0
 */
class TagCacheDependency extends CCacheDependency
{
    /**
     * @var string autoincrement(timestamp) param is used to
     * check if the dependency has been changed.
     */
    public $tag;
    /**
     * Cache component, by default used - cache
     *
     * @var CCache
     */
    public $cache;

    /**
     * Constructor.
     *
     * @param string $tag value of the tag for module
     * @param CCache $cache
     */
    public function __construct($tag = null, $cache = null)
    {
        $this->tag = $tag;
        $this->cache = ($cache) ? $cache : Yii::app()->cache;
    }

    /**
     * Generates the data needed to determine if dependency has been changed.
     * This method returns the integer(timestamp).
     *
     * @throws CException
     * @return mixed the data needed to determine if dependency has been changed.
     */
    protected function generateDependentData()
    {
        if($this->tag !== null)
        {
            $t = $this->cache->get($this->tag);
            if($t === false)
            {
                $t = microtime(true);
                $this->cache->set($this->tag, $t);
            }
            return $t;
        }
        else
        {
            throw new CException(Yii::t('yii', 'TagCacheDependency.tag cannot be empty.'));
        }
    }
}