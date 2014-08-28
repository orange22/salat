<?php
/**
 * WebUser
 */
class WebUser extends RWebUser
{
    /**
     * User data
     *
     * @var array
     */
    protected $data = array();

    /**
     * User roles
     *
     * @var array
     */
    protected $roles = null;

    public function init()
    {
        parent::init();
		if(!$this->isGuest)
        {
        	$user=User::model()->findByPk($this->getId());
			if(isset($user))
        	$this->data =$user->attributes;
        }
	}

    /**
     * Add flash
     *
     * @param string $key
     * @param mixed $value
     * @param mixed $defaultValue
     */
    public function addFlash($key, $value, $defaultValue = null)
    {
        $data = '';
        if($this->hasFlash($key))
        {
            $data = $this->getFlash($key, null, false);
        }

        if($data && $value)
        {
            $data .= '<br />';
        }
        $data .= $value;
        $this->setFlash($key, $data);
    }

    /**
     * Get user roles
     *
     * @return array
     */
    public function getRoles()
    {
        if($this->roles === null)
        {
            $this->roles = array_keys(Rights::getAssignedRoles());
        }

        return $this->roles;
    }

    /**
     * Get user display name
     *
     * @return string
     */
    public function getDisplayName()
    {
        if($this->isGuest)
            return 'Гость';

        $displayName = $this->getParam('display_name');

        return ($displayName ? $displayName : $this->getParam('email'));
    }
	
	public function getName()
    {
        $data = $this->getParam('name');

        if($data)
        return $data;
    }
	
	public function getPhone()
    {
        $data = $this->getParam('phone');

        if($data)
        return $data;
    }
	public function getEmail()
    {
        $data = $this->getParam('email');

        if($data)
        return $data;
    }
	public function getDeliveryAddr()
    {
        $data = $this->getParam('delivery_addr');

        if($data)
        return $data;
    }
	public function getDeliveryFrom()
    {
        $data = $this->getParam('delivery_from');

        if($data)
        return $data;
    }
	public function getDeliveryTill()
    {
        $data = $this->getParam('delivery_till');

        if($data)
        return $data;
    }

    /**
     * Get user data param
     *
     * @param string $attr User model attribute name
     * @return null|string Null if attribute is not set
     */
    public function getParam($attr)
    {
        if(!isset($this->data[$attr]))
            return null;

        return $this->data[$attr];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}