<?php
/**
 * DbAuthManager
 * Some caching
 */
class DbAuthManager extends RDbAuthManager
{
    /**
     * Local cache
     *
     * @var array
     */
    protected $cache = null;

    public function checkAccess($itemName, $userId, $params = array())
    {
        if(empty($this->cache['assignments'][$userId]))
        {
            $this->cache['assignments'][$userId] = $this->getAuthAssignments($userId);
        }
        return $this->checkAccessRecursive($itemName, $userId, $params, $this->cache['assignments'][$userId]);
    }

    protected function checkAccessRecursive($itemName, $userId, $params, $assignments)
    {
        // cache null items
        if(isset($this->cache['nullAuthItem'][$itemName]))
            return false;

        if(($item = $this->getAuthItem($itemName)) === null)
        {
            $this->cache['nullAuthItem'][$itemName] = true;
            return false;
        }

        Yii::trace('Checking permission "'.$item->getName().'"', 'app.components.DbAuthManager');
        if($this->executeBizRule($item->getBizRule(), $params, $item->getData()))
        {
            if(in_array($itemName, $this->defaultRoles))
            {
                return true;
            }
            if(isset($assignments[$itemName]))
            {
                $assignment = $assignments[$itemName];
                if($this->executeBizRule($assignment->getBizRule(), $params, $assignment->getData()))
                {
                    return true;
                }
            }
            if(!isset($this->cache['parents'][$itemName]))
            {
                $this->cache['parents'][$itemName] = $this->db->createCommand()
                    ->select('parent')
                    ->from($this->itemChildTable)
                    ->where('child=:name', array(':name' => $itemName))
                    ->queryColumn();
            }
            foreach($this->cache['parents'][$itemName] as $parent)
            {
                if($this->checkAccessRecursive($parent, $userId, $params, $assignments))
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function removeItemChild($itemName, $childName)
    {
        User::model()->refreshCache();
        return parent::removeItemChild($itemName, $childName);
    }

    public function revoke($itemName, $userId)
    {
        User::model()->refreshCache();
        return parent::revoke($itemName, $userId);
    }

    public function createAuthItem($name, $type, $description = '', $bizRule = null, $data = null)
    {
        User::model()->refreshCache();
        return parent::createAuthItem($name, $type, $description, $bizRule, $data);
    }

    public function removeAuthItem($name)
    {
        User::model()->refreshCache();
        return parent::removeAuthItem($name);
    }

    public function addItemChild($itemName, $childName)
    {
        User::model()->refreshCache();
        return parent::addItemChild($itemName, $childName);
    }

    public function assign($itemName, $userId, $bizRule = null, $data = null)
    {
        User::model()->refreshCache();
        return parent::assign($itemName, $userId, $bizRule, $data);
    }
}