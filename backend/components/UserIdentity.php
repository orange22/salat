<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_PASSWORD_GUESSING = 3;

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

        $user = User::model()->active()->findByAttributes(array(
            'email' => $this->username,
        ));

        if(!$user)
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;

            return !$this->errorCode;
        }

        $waitAuth = $this->waitNextAuth();

        if($waitAuth > 50)
        {
            $this->errorCode = self::ERROR_PASSWORD_GUESSING;
            $this->errorMessage = Yii::t('backend',
                'You have entred invalid password more than 3 times. Please wait {n} minute and try again.'
                .'|You have entred invalid password more than 3 times. Please wait {n} minutes and try again.',
                ceil($waitAuth / 60)
            );

            return !$this->errorCode;
        }


        if(!$skipPass && !$user->checkPass($this->password))
        {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;

            $this->logAuth($user, 0);

            return !$this->errorCode;
        }

        $this->setUserState($user);
        $this->errorCode = self::ERROR_NONE;

        $this->logAuth($user);

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
        $this->username = $model->email;
        Yii::app()->user->setState('email', $model->email);
        Yii::app()->user->setState('email', $model->email ? $model->email : '');
        Yii::app()->user->setState('displayName', $model->display_name ? $model->display_name : $model->email);

        return $this;
    }

    /**
     * Get wait time to next auth
     *
     * @return int Time in seconds
     */
    protected function waitNextAuth()
    {
        $sql = Yii::app()->db->createCommand();
        $sql->select('time, success')
            ->from('{{auth_log}}')
            ->where('ip = INET_ATON(:ip)')
            ->order('id DESC')
            ->limit(1);

        $last = $sql->queryRow(true, array(
            ':ip' => Yii::app()->request->getUserHostAddress(),
        ));

        if($last['success'] == 1)
            return 0;

        $sql->reset();

        $sql->select('COUNT(*)')
            ->from('{{auth_log}}')
            ->where('success = 0 AND ip = INET_ATON(:ip) AND time > :time');

        $failedCount = $sql->queryScalar(array(
            ':time' => time() - 300,
            ':ip' => Yii::app()->request->getUserHostAddress(),
        ));

        if($failedCount > 3)
        {
            return $last['time'] + 300 - time();
        }

        return 0;
    }

    /**
     * Log user authentications
     *
     * @param User $user
     * @param int $success
     */
    protected function logAuth($user, $success = 1)
    {
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
        return;
		$sql = Yii::app()->db->createCommand();
        $sql->insert('{{auth_log}}', array(
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => new CDbExpression('INET_ATON(:ip)', array(':ip' => Yii::app()->request->getUserHostAddress())),
            'time' => time(),
            'success' => $success
        ));
    }
}