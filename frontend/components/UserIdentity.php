<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
     * @var int User ID
     */
    private $_id;

    /**
     * Authenticates a user.
     *
     * @param bool $skipPass Skip pass cheking
     * @return boolean whether authentication succeeds.
     */
    public function authenticate($skipPass = false)
    {
        /** @var $user User */
        $user = User::model()->active()->findByAttributes(array(
            'email' => $this->username,
        ));

        if(!$user)
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;

            return !$this->errorCode;
        }

        if(!$skipPass && !$user->checkPass($this->password))
        {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;

            return !$this->errorCode;
        }

        $this->setUserState($user);
        $this->errorCode = self::ERROR_NONE;

        return !$this->errorCode;
    }

    /**
     * Get logged user ID
     *
     * @return int User ID
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set logged user state
     *
     * @param User $model
     * @return UserIdentity
     */
    public function setUserState($model)
    {
        $this->_id = $model->id;

        return $this;
    }
}