		<div id="main"><!--main start-->
			<div id="content">
				<div class="content-box">
					<h1>Регистрация</h1>
					<form action="/site/updateprofile/" method="post" class="register-form">
						<fieldset>
							<input type="hidden" name="page" value="<?=$page;?>"/>
							<div class="row<?=(yii::app()->user->getId()>0)?'':' required';?>"><!--class="required"-->
								<div class="label-holder">
									<label>Ваш e-mail</label>
								</div>
								<span class="req">&nbsp;</span>
								<label class="input-holder<?=(yii::app()->user->getId()>0)?' white' :'';?>"><!--class="error"-->
									<input<?=(yii::app()->user->getId()>0)?' disabled="disabled"' :' name="RegisterForm[email]"';?> type="text" value="<?=(isset($user['email']))?$user['email']:'';?>" />
								</label>
							</div>
							<div class="row<?=(yii::app()->user->getId()>0)?'':' required';?>"><!--class="required"-->
								<div class="label-holder">
									<label>Ваше имя</label>
								</div>
								<span class="req">&nbsp;</span>
								<label class="input-holder"><!--class="error"-->
									<input type="text" name="RegisterForm[name]" value="<?=(isset($user['name']))?$user['name']:'';?>" />
								</label>
							</div>
							<? if(Yii::app()->user->getId()<1){?>
							<div class="row required"><!--class="required"-->
								<div class="label-holder">
									<label>Пароль</label>
								</div>
								<span class="req">&nbsp;</span>
								<label class="input-holder"><!--class="error"-->
									<input type="password" name="RegisterForm[password]" />
								</label>
							</div>
							<div class="row required"><!--class="required"-->
								<div class="label-holder">
									<label>Пароль еще раз</label>
								</div>
								<span class="req">&nbsp;</span>
								<label class="input-holder"><!--class="error"-->
									<input type="password" name="RegisterForm[repeat_password]" />
								</label>
							</div>
							<?}?>
							<div class="row required">
								<div class="label-holder">
									<label>Номер Вашего телефона</label>
								</div>
								<span class="req">&nbsp;</span>
								<label class="input-holder"><!--class="error"-->
									<input name="RegisterForm[phone]" value="<?=(isset($user['phone']))?$user['phone']:'';?>" type="text" />
								</label>
							</div>
							<div class="row">
								<div class="label-holder">
									<label>Адрес доставки</label>
								</div>
								<span class="req">&nbsp;</span>
								<label class="textarea-holder">
									<textarea name="RegisterForm[delivery_addr]" rows="5" cols="30"><?=(isset($user['delivery_addr']))?$user['delivery_addr']:'';?></textarea>
								</label>
							</div>
							<div class="btn-right">
								<div class="green-btn">
									<span>Сохранить</span>
									<input type="submit" value="Сохранить" />
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		<div class="see-menu btn-holder center">
			<a href="/#top" class="green-btn">
				<span><?=Yii::t('frontend', 'Actualdish');?></span>
			</a>
		</div>
    </div>