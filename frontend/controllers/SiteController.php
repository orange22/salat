<?php

class SiteController extends FrontController
{
    public function init()
    {
       parent::init();
       Yii::import('common.extensions.yii-mail.*');
    }
	
	public function actionIndex()
	{
        $this->render('index');
	}
		
	public function actionIndexold()
	{
		$topdishes=Dish::model()->with(array('dishImages'=>array('sort'),'courses'=>array('with'=>array('coursetype'=>array('with'=>'coursetypeimage'))),'dishtype'=>array('with'=>'dishtypeimage')))->sort()->active()->findAll('t.main=1');
		$teasers=Teaser::model()->with('image')->sort()->active()->findAll();
		$this->render('index',array('topdishes'=>$topdishes,'teasers'=>$teasers));
	}
	
	
	public function actionContacts() {
		$managers=User::model()->with(array('userUsertypes'=>array('joinType'=>'inner join')))->sort()->active()->findAll('userUsertypes.id=3');
		$this->render('contacts',array('managers'=>$managers));
	}
	
	public function actionTeam() {
		$team=User::model()->with(array('image','userUsertypes'=>array('joinType'=>'inner join')))->sort('t.sort ASC')->active()->findAll('userUsertypes.id=2');
		//CVarDumper::dump($team[0],10,true);
		$this->render('team',array('team'=>$team));
	}
	
	public function actionDelivery() {
		$delivery=Delivery::model()->sort()->active()->findAll();
		$this->render('delivery',array('delivery'=>$delivery));
	}
	
	public function actionFaq() {
		$faq=Faq::model()->sort()->active()->findAll();
		$this->render('faq',array('faq'=>$faq));
	}
	
	/*
	public function actionRegister() {
			//$managers=User::model()->with(array('userUsertypes'=>array('joinType'=>'inner join')))->findAll('userUsertypes.id=3');
			$this->render('register');
		}*/
	public function actionSubscribe() {
		//$managers=User::model()->with(array('userUsertypes'=>array('joinType'=>'inner join')))->findAll('userUsertypes.id=3');
		//if(Yii::app()->user->getId()>0){
			$title='Рассылка';
			$text='';
			
			$subscriber=Subscriber::model()->findByAttributes(array(
			'email' => $_POST['email']
			));
			
			if(isset($subscriber)){
				$text='Данные email уже подписан на рассылку нашего сайта.';
			}else{
				$subscriber=new Subscriber;
				$subscriber->email=$_POST['email'];
				$subscriber->save();
				if($subscriber->id>0){
					$text='Вы успешно подписались на рассылку нашего сайта.';
				}
			}
			
			$this->render('subscribe',array('title'=>$title, 'text'=>$text));
		//}
		//else
		//$this->redirect('/');	
	}
	public function actionRegister() {
		//$managers=User::model()->with(array('userUsertypes'=>array('joinType'=>'inner join')))->findAll('userUsertypes.id=3');
		//if(Yii::app()->user->getId()>0){
			if(isset($_GET['page']))
			$page=$_GET['page'];
			else 
			$page=null;
				
			$user=Yii::app()->user->getData();
			$this->render('register',array('user'=>$user, 'page'=>$page));
		//}
		//else
		//$this->redirect('/');	
	}
	

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	public function actionLoginoauth()
    {
    	$service = Yii::app()->request->getQuery('service');
        if (isset($service)) {
            $authIdentity = Yii::app()->eauth->getIdentity($service);
            $authIdentity->redirectUrl = Yii::app()->request->getQuery('page');
			$authIdentity->cancelUrl = $this->createAbsoluteUrl('/');

            if ($authIdentity->authenticate()) {
                $identity = new EAuthUserIdentity($authIdentity);
				
              
                              if ($identity->authenticate()) {
                              	  $checkuser=User::model()->findByAttributes(array(
							           'email' => $identity->getEmail()
							      ));
								  $user=User::model()->fbUser($identity);
								 
								   $model=new LoginForm;
								   $model->attributes=$user->attributes;
								  if($model->fblogin()){
								  	//echo $authIdentity->redirectUrl;
								  	//die();
						             if($checkuser)
						             $this->redirect($authIdentity->redirectUrl);
									 else{
									 $this->redirect(array('/site/register/', 'page'=> urlencode(trim($authIdentity->redirectUrl,'/'))));
									 }
									}
									else{
									  $authIdentity->cancel();
									}
								  
                              }
                              else {
                                  // close popup window and redirect to cancelUrl
                                  $authIdentity->cancel();
                              }
              
            }
            // Something went wrong, redirect to login page
            $this->redirect(array('/'));
			Yii::app()->end();
		}
	}
	public function actionRegistered(){
		//CVarDumper::dump($_POST);	
		parent::actionMessage('Регистрация','Вы успешно зарегистрировались на нашем сайте');
	}
	public function actionUpdateprofile()
    {
    	$model=new RegisterForm;
		
    	if(isset($_POST['ajax']) && $_POST['ajax']==='register-form')
        {
            $model->attributes=$_POST['RegisterForm'];
			$model->validate();
			$errors=$model->getErrors();
			if($_POST['field']=='RegisterForm[repeat_password]')
			$_POST['field']='RegisterForm[password]';
			preg_match("'RegisterForm\[(\w+)\]?'", $_POST['field'], $match);
			$errorstatus=false;
			if(array_key_exists($match[1], $errors))
			$errorstatus=$errors[$match[1]];
			$this->sendJsonResponse(array(
            'error' => $errorstatus,
        	));
            Yii::app()->end();
        }	
    	if(Yii::app()->user->getId()>0){
			$user=User::model()->findByPk(Yii::app()->user->getId());
			$user->name=$_POST['RegisterForm']['name'];
			$user->phone=$_POST['RegisterForm']['phone'];
			$user->delivery_addr=$_POST['RegisterForm']['delivery_addr'];
			$user->save();
			if(isset($_POST['page']))
			$this->redirect('/'.$_POST['page']);
			else
			$this->redirect('/');
		}else{
			$model->attributes=$_POST['RegisterForm'];
			if($model->validate() && $model->register()){
				$message = new YiiMailMessage;
				$message->setBody('
				Здравствуйте!<br><br>
				Вы успешно зарегистрировались на сайте '.$_SERVER['HTTP_HOST'].'. Ваши данные:<br><br>
				Ваш логин: '.$_POST['RegisterForm']['email'].'<br>
				Ваш пароль: '.$_POST['RegisterForm']['password'].'<br><br>
				Вы всегда сможете изменить эти данные в личном кабинете на сайте.<br><br>
				Наш телефон: '.Option::getOpt('mainphone').'<br>
				С уважением,<br>
				Личный Повар
				', 'text/html');
				$message->subject = 'Регистрация у Личного Повара';
				$message->addTo($_POST['RegisterForm']['email']);
				$message->from = array(Yii::app()->params['adminEmail']=>'Личный Повар');
				Yii::app()->mail->send($message);
				$this->redirect('/site/registered/');
			}
			else
				$this->redirect('/');
			//CVarDumper::dump($model->getErrors(),10);
		}
		
	}
	public function actionRetrieve()
    {
    	if(Yii::app()->user->getId()>0)
		$this->redirect('/');
    	if($user=User::model()->find('t.email=:email',array(':email'=>$_POST['email']))){
				$key=md5(microtime(). $_POST['email'].'191084' . rand());
				Key::model()->deleteAll('user_id=:user_id',array('user_id'=>$user->id));
				
				$newtoken = new Key;
				$newtoken->token=$key;
				$newtoken->user_id=$user->id;
				$newtoken->date_create=new CDbExpression('NOW()');
				$newtoken->save();
				if($newtoken->id>0){
				$message = new YiiMailMessage;
				$message->setBody('
				Здравствуйте!<br><br>
				Вы запросили новый пароль на сайте '.$_SERVER['HTTP_HOST'].':<br><br>
				В связи с требованиями безопасности мы храним пароли в зашифрованом виде. Для получения нового пароля перейдите по следующей ссылке:<br><br>
				http://'.$_SERVER['HTTP_HOST'].'/site/getnewpassword/?key='.$key.'<br><br>
				Если вы не запрашивали получение пароля просто проигнорируйте это сообщение.<br><br>
				Наш телефон: '.Option::getOpt('mainphone').'<br>
				С уважением,<br>
				Личный Повар
				', 'text/html');
				$message->subject = 'Запрос пароля у Личного Повара';
				$message->addTo($user->email);
				$message->from = array(Yii::app()->params['adminEmail']=>'Личный Повар');
				Yii::app()->mail->send($message);
				self::actionMessage('Восстановление пароля','Инструкция по получению нового пароля выслана на указаный email.');
				}else
				self::actionMessage('Восстановление пароля','Указаный email не зарегистрирован на нашем сайте.');
		}
		self::actionMessage('Восстановление пароля','Указаный email не зарегистрирован на нашем сайте.');
	}
	public function actionGetnewpassword()
    {
    		if(Yii::app()->user->getId()>0)
			$this->redirect('/');
    		if($token=Key::model()->find('t.token=:token AND DATE_ADD(t.date_create, INTERVAL 5 HOUR) > NOW()',array('token'=>$_GET['key']))){
    			$newPassword=User::generatePassword();
				$user=User::model()->findByPk($token->user_id);
				$user->password=$newPassword;
				$user->save();
				$message = new YiiMailMessage;
				$message->setBody('
				Здравствуйте!<br><br>
				Вы подтвердили запрос на создание нового пароля на сайте '.$_SERVER['HTTP_HOST'].':<br><br>
				Ваш логин: '.$user->email.'<br>
				Ваш новый пароль: '.$newPassword.'<br><br>
				Вы всегда сможете изменить эти данные в личном кабинете на сайте.<br><br>
				Наш телефон: '.Option::getOpt('mainphone').'<br>
				С уважением,<br>
				Личный Повар
				', 'text/html');
				$message->subject = 'Новый пароль для Личного Повара';
				$message->addTo($user->email);
				$message->from = array(Yii::app()->params['adminEmail']=>'Личный Повар');
				Yii::app()->mail->send($message);
				if($user->id>0)
				$token->delete();
				self::actionMessage('Восстановление пароля','Новые параметры доступа к сайту отправлены на ваш email.');
    		}else
			self::actionMessage('Восстановление пароля','Cсылка для восстановления устарела или не существует.');
	}
	public function actionMessage($title=null,$message=null)
    {
    	$this->render('message',array('title'=>$title,'message'=>$message));
	}

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
    	
        header('Content-Type: application/json; charset=utf-8');
        $model=new LoginForm;
        // collect user input data
        if(isset($_POST['LoginForm']))
            {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()){
              echo json_encode(array('error' => 0));
			}
			else{
			  echo json_encode(array('error' => 1, 'status' => 'Вы ввели неправильный логин или пароль'));
			}
		}
		Yii::app()->end();
	}
    public function actionDiet()
    {

        header('Content-Type: application/json; charset=utf-8');
        $model=new DietForm;
        // collect user input data
        if(isset($_POST['DietForm']))
        {
            $model->attributes=$_POST['DietForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->save()){
                echo json_encode(array('error' => 0));
            }
            else{
                echo json_encode(array('error' => 1, 'status' => 'Вы заполнили не все поля'));
            }
        }
        Yii::app()->end();
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
		unset($_COOKIE['PHPSESSID']);
        $this->redirect(Yii::app()->homeUrl);
    }

}