<div id="main"><!--main start-->
			<div id="content">
				<div class="content-box">
					<h1>Контактная информация</h1>
					<div class="contacts">
						<div class="row">
							<h2><?=Option::getOpt('contact_address');?></h2>
							<p><?=Option::getOpt('contact_worktime');?></p>
						</div>
						<div class="row">
							<h2>Телефон</h2>
							<p>По всем вопросам вы можете обращаться к нашим менеджерам:</p>
							<ul class="phone-list">
								<? foreach($managers as $manager){?>
								<li><?=preg_replace("/[^0-9,.]/", "", $manager->phone);?> <span class="name"><?=$manager->name;?></span></li>
								<?}?>
							</ul>
						</div>
						<div class="row">
							<h2>E-mail</h2>
							<a target="_bblank" href="mailto:<?=Option::getOpt('contact_email');?>" class="mail"><?=Option::getOpt('contact_email');?></a>
						</div>
						<div class="map-holder">
						<!--http://maps.google.ru/maps?f=q&amp;source=s_q&amp;hl=ru&amp;geocode=&amp;q=12+%D0%A0%D0%B8%D0%B6%D1%81%D0%BA%D0%B0%D1%8F+%D1%83%D0%BB%D0%B8%D1%86%D0%B0,+%D0%9A%D0%B8%D0%B5%D0%B2,+%D0%B3%D0%BE%D1%80%D0%BE%D0%B4+%D0%9A%D0%B8%D0%B5%D0%B2,+%D0%A3%D0%BA%D1%80%D0%B0%D0%B8%D0%BD%D0%B0&amp;aq=0&amp;oq=%D0%9A%D0%B8%D0%B5%D0%B2,+%D1%83%D0%BB.+%D0%A0%D0%B8%D0%B6%D1%81%D0%BA%D0%B0%D1%8F,+12&amp;sll=55.354135,40.297852&amp;sspn=22.093388,67.631836&amp;ie=UTF8&amp;hq=&amp;hnear=%D0%A0%D0%B8%D0%B6%D1%81%D0%BA%D0%B0%D1%8F+%D1%83%D0%BB.,+12,+%D0%9A%D0%B8%D0%B5%D0%B2,+%D0%B3%D0%BE%D1%80%D0%BE%D0%B4+%D0%9A%D0%B8%D0%B5%D0%B2,+%D0%A3%D0%BA%D1%80%D0%B0%D0%B8%D0%BD%D0%B0&amp;t=m&amp;ll=50.479247,30.441742&amp;spn=0.018898,0.053988&amp;z=14&amp;iwloc=A&amp;output=embed-->
						<!--
						<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com.ua/maps?hl=ru&amp;ie=UTF8&amp;t=h&amp;ll=50.45441,30.447997&amp;spn=0.002391,0.00456&amp;z=17&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com.ua/maps?hl=ru&amp;ie=UTF8&amp;t=h&amp;ll=50.45441,30.447997&amp;spn=0.002391,0.00456&amp;z=17&amp;source=embed" style="color:#0000FF;text-align:left">Просмотреть увеличенную карту</a></small>
						-->
						<?=Option::getOpt('shopmap');?>
						</div>
					</div>
				</div>
			</div>
			<?=$this->renderWidgets();?>
		<div class="see-menu btn-holder center">
			<a href="/#top" class="green-btn">
				<span><?=Yii::t('frontend', 'Actualdish');?></span>
			</a>
		</div>
</div><!--main end-->