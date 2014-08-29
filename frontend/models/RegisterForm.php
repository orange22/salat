<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	public $email;
	public $name;
	public $phone;
	public $delivery_addr;
	public $password;
	public $repeat_password;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email, password, repeat_password, name ,phone', 'required'),
			// password needs to be authenticated
			array('email', 'uniqueemail'),
			array('password', 'length', 'min'=>6, 'max'=>12),
			// when in register scenario, password must match password2
			array('delivery_addr', 'safe'),
			array('password', 'compare', 'compareAttribute'=>'repeat_password'),
			
		);
	}

	public function uniqueemail()
		{
			if(User::model()->find('email="'.$this->email.'"'))
			$this->addError('email','Пользователь с таким email уже зарегистрирован');
		}
	

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function register(){
		$duration=false;	
		
		if(!$this->getErrors()){
			
			$user=new User;
			//$this->unsetAttributes(array('repeat_password'=>'repeat_password'));
			$user->attributes=$this->attributes;
			$user->save();
			if($this->_identity===null)
			{
				$this->_identity=new UserIdentity($this->email,$this->password);
				$this->_identity->authenticate(true);
			}
			if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
			{
				$duration = 3600*24*1;
				Yii::app()->user->login($this->_identity,$duration);
				return true;
			}
			
			if($user->id>0)
			return true;
		}
	}
	/*
	public function login()
		{
			if($this->_identity===null)
			{
				$this->_identity=new UserIdentity($this->email,$this->password);
				$this->_identity->authenticate();
			}
			if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
			{
				$duration = 3600*24*1;
				Yii::app()->user->login($this->_identity,$duration);
				return true;
			}
			else
				return false;
		}*/
	
	
}
