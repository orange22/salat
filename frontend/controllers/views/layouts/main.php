<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=htmlspecialchars($this->seo_title);?></title>
	<meta name="base" content="/" />
	<meta name="title" content="<?=htmlspecialchars($this->seo_title);?>" />
	<meta name="description" content="<?=htmlspecialchars($this->seo_description);?>" />
	<meta name="keywords" content="<?=htmlspecialchars($this->seo_keywords);?>" />
	<meta property="og:url" content="http://<?=$_SERVER['HTTP_HOST'];?><?=$_SERVER['REQUEST_URI'];?>" />
	<meta property="og:title" content="<?=htmlspecialchars($this->og_title);?>" />
	<meta property="og:description" content="<?=htmlspecialchars($this->og_description);?>" />
	<meta property="og:image" content="<?=(isset($this->og_image))?'http://'.$_SERVER['HTTP_HOST'].'/'.$this->og_image:'http://'.$_SERVER['HTTP_HOST'].Option::getOpt('seoimage').'';?>" />
	<meta property="fb:app_id" content="509418155763464"/>
	<meta property="fb:admins" content="dmitriy.bozhok"/>
	<meta property="fb:admins" content="alexander.interio"/>
	<meta property="fb:admins" content="prorokartem"/>
	<meta property="fb:admins" content="andrei.denisenko.58"/>
	<link rel="stylesheet" href="/css/all.css?v=4" type="text/css" />
	<link rel="stylesheet" href="/css/opacity.css?v=2" type="text/css" />
	<link rel="stylesheet" href="/css/main.css?v=11032014" type="text/css" />
	<link rel="stylesheet" href="/css/jquery.powertip.css" type="text/css" />


	<link rel="stylesheet" href="/css/jquery.fancybox.css" type="text/css" />
	<link rel="stylesheet" href="/css/jquery.fancybox-thumbs.css" type="text/css" />
	<link rel="shortcut icon" href="/images/favicon2.ico" type="image/x-icon" />

	<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="css/ie8.css" media="screen"/><![endif]-->
	<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen"/><![endif]-->
	<!--<script type="text/javascript" src="/js/clear-form-fields.js"></script>-->
	<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery.main.js?v=<?=time();?>"></script>
	<script type="text/javascript" src="/js/jquery.powertip.js"></script>
	<script type="text/javascript" src="/js/main.js?v=11032014"></script>
	<!--[if lt IE 9]><script type="text/javascript" src="js/pie.js"></script><![endif]-->
	<script type="text/javascript" src="/js/jquery.pseudo.js"></script>
	<script type="text/javascript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="/js/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="/js/jquery.fancybox-thumbs.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox-thumb").fancybox({
				prevEffect	: 'none',
				nextEffect	: 'none',
				helpers	: {
					title	: {
						type: 'outside'
					},
					thumbs	: {
						width	: 50,
						height	: 50
					}
				}
			});
		});
		$(document).ready(function() {
			$(".fancybox-thumb2").fancybox({
				prevEffect	: 'none',
				nextEffect	: 'none',
				arrows : false,
				mouseWheel : false,
			});
		});
	</script>
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-38580543-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	<? if($this->id=='cart' && $this->action->id=='order'){?>
	<script type="text/javascript">
	var fb_param = {};
	fb_param.pixel_id = '6008206237109';
	fb_param.value = '0.00';
	(function(){
	  var fpw = document.createElement('script');
	  fpw.async = true;
	  fpw.src = '//connect.facebook.net/en_US/fp.js';
	  var ref = document.getElementsByTagName('script')[0];
	  ref.parentNode.insertBefore(fpw, ref);
	})();
	</script>

	<!-- Google Code for confirm_order Conversion Page -->
	<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 988716451;
	var google_conversion_language = "en";
	var google_conversion_format = "3";
	var google_conversion_color = "ffffff";
	var google_conversion_label = "-GLcCPXhzQQQo7u61wM";
	var google_conversion_value = 0;
	/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
	<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/988716451/?value=0&amp;label=-GLcCPXhzQQQo7u61wM&amp;guid=ON&amp;script=0"/>
	</div>
	</noscript>
	<?}?>




<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6008206237109&amp;value=0" /></noscript>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=509418155763464";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="wrapper-holder"><!--new-year-->
	<div id="wrapper"><!--wrapper start-->
		<div class="header-holder<?=(($this->id=='site' && $this->action->id=='index') || $this->id=='blog')?' '.$this->id.'-holder':' small';?>">
			<div id="header"><!--header start-->
				<div class="row">
                    <div id="diet-button"><a href="">Вопрос шеф-повару</a></div>
                    <h1 class="logo"><a href="/">Личный повар</a></h1>
					<div class="head-contacts">
						<p>c 10.00 до 21.00 ежедневно</p>
						<div class="phone"><?=Option::getOpt('mainphone');?></div>
						<div class="free-delivery">Бесплатная доставка <a href="#" rel='Доставка осуществляется "день в день" в удобное для вас место в Киеве.' class="ico-holder call-popup"><img src="/images/ico15.png" width="20" height="20" alt="image description" /></a></div>
					</div>
					<? if(Yii::app()->user->getId()>0){?>
					<div class="user-tools-holder">
						<div class="user"><a href="/cabinet/"><em><?=Yii::app()->user->getDisplayName();?></em></a></div>
						<ul class="user-tools">
							<li><a href="/cabinet/"><span><strong class="ico-holder"><img src="/images/ico06.png" width="18" height="18" alt="image description" /></strong></span></a></li>
							<li><a href="/cabinet/settings/"><span><strong class="ico-holder"><img src="/images/ico07.png" width="18" height="18" alt="image description" /></strong></span></a></li>
							<li class="cart"><a href="/cart/"><span><strong  class="ico-holder"><img src="/images/ico08.png" width="18" height="18" alt="image description" /></strong><em><?=(Yii::app()->controller->action->id=='order')?0:$this->cart->getItemsCount();?></em></span></a></li>
						</ul>
					</div>
					<?}else{
					if($this->cart->getItemsCount()>0){
					?>
					<div class="user-tools-holder">
						<div class="user"><a><em><?=Yii::app()->user->getDisplayName();?></em></a></div>
						<ul class="user-tools">
							<li class="cart"><a href="/cart/"><span><strong  class="ico-holder"><img src="/images/ico08.png" width="18" height="18" alt="image description" /></strong><em><?=(Yii::app()->controller->action->id=='order')?0:$this->cart->getItemsCount();?></em></span></a></li>
						</ul>
					</div>
					<?}else{?>
					<a href="/howitworks/" class="delivery-box">
						<strong>Доставка</strong>
						<span>продуктов с рецептами</span>
						<em class="deco"></em>
					</a>
					<?}}?>
				</div>
				<ul id="nav">
				    <li><a href="/dish/category/<?=$this->firstcategory_id;?>/"><span>Меню</span></a></li>
					<!--<li><a href="/page/about/"><span>О проекте</span></a></li>-->
					<li><a href="/howitworks/"><span>Как это работает</span></a></li>
					<li><a href="/site/delivery/"><span>Оплата и доставка</span></a></li>
                    <li><a href="/site/team/"><span>Команда</span></a></li>
                    <li><a href="/recipe/"><span>Рецепты</span></a></li>
                    <li><a class="blogmenu" href="/blog/"><span>Блог</span></a></li>
                    <!--<li><a class="redmenu" href="/dish/category/17"><span>Новый Год</span></a></li>-->
                    <? if(Yii::app()->user->getId()>0){?>
					<li class="logout"><a href="/site/logout/"><span>Выйти<i></i></span></a></li>
					<?}else{?>
					<li class="login"><a href="#"><span>Войти<i></i></span></a></li>
					<?}?>
				</ul>
				<?=$this->renderTopDishes();?>
			</div><!--header end-->
		</div>
		<?=$content;?>
		<!-- end catalog-list -->
			<? if(yii::app()->controller->id!='blog'){?>
            <div class="like-row">
				<div class="ico-frame">
					<img src="/images/ico24.png" width="76" height="70" alt="image description" />
				</div>
				<div class="like-frame">
					<div class="fb-like" data-href="https://www.facebook.com/Lpovar" data-width="824" data-show-faces="true" data-send="false" data-max-rows="1"></div>
					<!--<div class="fb-facepile" data-app-id="509418155763464" data-href="https://www.facebook.com/Lpovar" data-action="Comma separated list of action of action types" data-width="824" data-max-rows="1"></div>-->
				</div>
			</div>
            <?}?>
	</div><!--wrapper end-->
</div>
<div class="footer-holder">
	<div id="footer"><!--footer start-->
	    <?=$this->renderPartners();?>
		<div class="footer-row">
			<div class="col">
				<ul class="footer-nav">
					<li>
						<ul>
							<li><a href="/page/about/">О проекте</a></li>
							<li><a href="/howitworks/">Как это работает</a></li>
							<li><a href="/site/delivery/">Оплата и доставка</a></li>
							<li><a href="/site/team/">Команда</a></li>
						</ul>
					</li>
					<li>
						<ul>
							<li><a href="/site/faq/">Частые вопросы</a></li>
							<li><a href="/press/">Пресса о нас</a></li>
							<li><a href="/site/contacts/">Контакты</a></li>
                            <li><a href="/action/">Акции</a></li>
						</ul>
					</li>
				</ul>
				<div class="copy">© <?=date('Y');?> Личный Повар &nbsp;|&nbsp; <a href="/page/terms/">Условия использования</a></div>
			</div>
			<div class="col">
				<form action="/site/subscribe/" method="post" class="subscribe">
					<fieldset>
						<div class="title">Узнавайте первыми о новинках Личного Повара!</div>
						<div class="row">
							<label class="input-holder">
								<input type="text" name="email" value="Ваш e-mail"/>
							</label>
							<div class="btn-holder">
								<div class="green-btn">
									<span>Подписаться</span>
									<input type="submit" value="Подписаться" />
								</div>
							</div>
						</div>
						<a href="/page/subscription/">Посмотреть пример рассылки</a>
					</fieldset>
				</form>
				<div class="title">Следите за нами</div>
				<ul class="social-list">
					<li><a target="_blank" href="https://www.facebook.com/Lpovar"><img src="/images/fb.png" width="40" height="40" alt="Facebook" /></a></li>
					<li><a target="_blank" href="http://vk.com/lpovar"><img src="/images/vk.png" width="40" height="40" alt="Vkontakte" /></a></li>
					<li><a target="_blank" href="https://twitter.com/lichnyipovar"><img src="/images/tw.png" width="40" height="40" alt="Twitter" /></a></li>
					<li><a target="_blank" href="http://instagram.com/lpovar"><img src="/images/ig.png" width="40" height="40" alt="Instagram" /></a></li>
					<li><a target="_blank" href="https://plus.google.com/116299644278391831816" rel="publisher"><img src="/images/gp.png" width="40" height="40" alt="Google+" /></a></li>

				</ul>
			</div>
		</div>
		<span class="deco"></span>
	</div><!--footer end-->
</div>
<div class="to-up-holder">
	<a href="#" class="to-up">Наверх</a>
</div>
<div class="popup-holder" id="login"><!--popup start-->
	<div class="bg">&nbsp;</div>
	<div class="popup">
		<div class="popup-frame">
			<form action="/site/login/" method="post" id="login-form">
				<fieldset>
					<div class="description">
						Введите ваш e-mail и пароль <br /> или войдите с помощью Facebook</div>
					<div class="row">
						<label class="input-holder"><!--class="error"-->
							<input type="text" name="LoginForm[email]" placeholder="Ваш e-mail" value="" />
						</label>
					</div>
					<div class="row">
						<label class="input-holder"><!--class="error"-->
							<input type="password" name="LoginForm[password]"  placeholder="Пароль" value="" />
						</label>
					</div>
					<div class="row">
						<div class="btn-holder right">
							<div class="green-btn">
								<span>Войти</span>
								<input type="submit" value="Войти" />
							</div>
							<div class="remember">
								<input id="lbl101" type="checkbox" value="1" name="LoginForm[rememberMe]" class="checkbox" />
								<label for="lbl101">Запомнить пароль</label>
							</div>
						</div>
					</div>
					<div class="attention"><!--class="active"-->
						<div class="text"></div>
					</div>
					<div class="row links">
						<a href="#"  class="forgot">Забыли пароль?</a>
						<a href="/site/register/" class="register">Регистрация</a>
					</div>
					<div class="row">
						<? $this->widget('common.extensions.yii-eauth.EAuthWidget', array('action' => 'site/loginoauth'));?>
					</div>
				</fieldset>
			</form>
			<a href="#" class="close"></a>
		</div>
		<span class="popup-stroke"></span>
	</div>
</div><!--popup end-->
<div class="popup-holder" id="diet"><!--popup start-->
    <div class="bg">&nbsp;</div>
    <div class="popup">
        <div class="popup-frame">
            <form action="/site/diet/" method="post" id="diet-form">
                <fieldset>
                    <div class="description">
                        Наш Шеф-повар ответит вам в ближайшее время на ваш email</div>
                    <div class="row">
                        <label class="input-holder"><!--class="error"-->
                            <input type="text" name="DietForm[title]" placeholder="Вашe имя" value="" />
                        </label>
                    </div>
                    <div class="row">
                        <label class="input-holder"><!--class="error"-->
                            <input type="text" name="DietForm[email]" placeholder="Ваш e-mail" value="" />
                        </label>
                    </div>
                    <div class="row">
                        <label class="textarea-holder"><!--class="error"-->
                            <textarea placeholder="Напишите свой вопрос" name="DietForm[detail_text]"></textarea>
                            <!--<input type="password" name="DietForm[password]"  placeholder="Пароль" value="" />-->
                        </label>
                    </div>
                    <div class="row">
                        <div class="btn-holder right">
                            <!--<img src="/images/diet-logo.png"/>-->
                            <div class="green-btn">
                                <span>Отправить</span>
                                <input type="submit" value="Отправить" />
                            </div>
                        </div>
                    </div>
                    <!--<div class="attention">
                        <div class="text"></div>
                    </div>-->
                </fieldset>
            </form>
            <a href="#" class="close"></a>
        </div>
        <span class="popup-stroke"></span>
    </div>
</div><!--popup end-->
<div class="popup-holder" id="remind"><!--popup start-->
	<div class="bg">&nbsp;</div>
	<div class="popup">
		<div class="popup-frame">
			<form action="/site/retrieve/" method="post">
				<fieldset>
					<div class="description double">Для того, чтобы восстановить пароль, <br />необходимо указать e-mail,  <br />
на который была зарегистрирована учетная запись.</div>
					<div class="row">
						<label class="input-holder"><!--class="error"-->
							<input type="text" name="email" value="Ваш e-mail" />
						</label>
					</div>
					<div class="row">
						<div class="btn-holder right">
							<div class="green-btn">
								<span>Напомнить</span>
								<input type="submit" value="Напомнить" />
							</div>
						</div>
					</div>
					<div class="attention"><!--class="active"-->
						<div class="text"></div>
					</div>
					<div class="row links">
						<a href="#"  class="forgot unactive">Забыли пароль?</a>
						<a href="/site/register/" class="register">Регистрация</a>
					</div>
					<div class="row">
						<? $this->widget('common.extensions.yii-eauth.EAuthWidget', array('action' => 'site/loginoauth'));?>
					</div>
				</fieldset>
			</form>
			<a href="#" class="close"></a>
		</div>
		<span class="popup-stroke"></span>
	</div>
</div><!--popup end-->
<div class="popup-holder" id="promo-code"><!--popup start-->
	<div class="bg">&nbsp;</div>
	<div class="popup">
		<div class="popup-frame">
			<form id="disccode" action="#">
				<fieldset>
					<div class="description">Введите промо-код:</div>
					<div class="row">
						<div class="btn-holder right">
							<div class="green-btn">
								<span>Применить</span>
								<input type="submit" value="Применить" />
							</div>
							<label class="input-holder"><!--class="error"-->
								<input type="text" name="code" />
							</label>
						</div>
					</div>
				</fieldset>
			</form>
			<a href="#" class="close"></a>
		</div>
		<span class="popup-stroke"></span>
	</div>
</div><!--popup end-->

<!--popup end-->
<div class="popup-holder" id="default-popup"><!--popup start-->
    <div class="bg">&nbsp;</div>
    <div class="popup">
        <div class="popup-frame">
            <p>Бесплатная доставка "сегодня на сегодня" по всему Киеву.<br>
                Время работы: с 10.00 до 21.00 / 7 дней в неделю.<br>
            </p>
            <div class="btn-holder" >
                <a href="#" class="green-btn close-popup">
                    <span>ок</span>
                </a>
            </div>
            <a href="#" class="close"></a>
        </div>
        <span class="popup-stroke"></span>
    </div>
</div><!--popup end-->
<?if(!Option::getOpt('buy') && $this->display_closed_popup){?>
<div class="popup-holder" id="closed-popup" style="display: block;"><!--popup start-->
    <div class="bg">&nbsp;</div>
    <div class="popup">
        <div class="popup-frame">
            <p>Друзья, всех с праздником!<br>
                У нас сегодня ооочень много доставок, так что пока мы не принимаем заказы!<br>
                Следите за обновлениями!
            </p>
            <div class="btn-holder" >
                <a href="#" class="green-btn close-popup">
                    <span>ок</span>
                </a>
            </div>
            <a href="#" class="close"></a>
        </div>
        <span class="popup-stroke"></span>
    </div>
</div>
<?}?><!--popup end-->
<!--popup end-->
<div class="popup-holder" id="buy-popup"><!--popup start-->
    <div class="bg">&nbsp;</div>
    <div class="popup">
        <div class="popup-frame">
            <p>Товар успешно добавлен в корзину.<br>
             Вы хотите продолжить покупки или перейти к оплате?<br>
            </p>
                            <div class="btn-holder" >
                                <a href="/cart/" id="tocart" class="red-btn">
                                    <span>К оплате</span>
                                </a>
                                <a href="#" class="green-btn close-popup">
                                    <span>Продолжить</span>
                                </a>
                            </div>
            <a href="#" class="close"></a>
        </div>
        <span class="popup-stroke"></span>
    </div>
</div><!--popup end-->
<!--popup end-->
<!--popup start-->
<?
if(isset($_GET['banner']) || $this->display_popup){
//if(date('Ymd')<20131230){?>
<div class="popup-holder" id="tefal-popup">
    <div class="bg">&nbsp;</div>
    <div class="popup">
        <img src="/images/tefal-banner.png"/>
            <a href="#" class="close"></a>
        <div class="preorder"><a href="/dish/category/3"><span>Сделать заказ<i></i></span></a></div>
    </div>
</div>
<!--<div class="popup-holder" id="newyear-popup">
    <div class="bg">&nbsp;</div>
    <div class="popup">
        <img src="/images/newyear.png?v=2"/>
        <a href="#" class="close"></a>
        <div class="preorder"><a href="/dish/category/17"><span>Сделать предзаказ<i></i></span></a></div>
    </div>
</div>->
<?//}elseif(date('Ymd')>20131229 && date('Ymd')<20140104){?>
<!--<div class="popup-holder" id="newyear-popup2">
   <div class="bg">&nbsp;</div>
   <div class="popup">
       <img src="/images/newyear_closed.png?v=1"/>
          <a href="#" class="close"></a>
   </div>
</div>-->
<?}//}?>
<!--popup end-->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter20811406 = new Ya.Metrika({id:20811406,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/20811406" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<!-- Google Code for &#1058;&#1077;&#1075; &#1088;&#1077;&#1084;&#1072;&#1088;&#1082;&#1077;&#1090;&#1080;&#1085;&#1075;&#1072; -->
<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 988716451;
    var google_conversion_label = "GlwWCP3gzQQQo7u61wM";
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/988716451/?value=0&amp;label=GlwWCP3gzQQQo7u61wM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
<script type="text/javascript">
    adroll_adv_id = "Q2Z2F3LO3BFXFKKFYPS4L2";
    adroll_pix_id = "BHUIVDTT7FBXDD34P3GCNI";
    (function () {
        var oldonload = window.onload;
        window.onload = function(){
            __adroll_loaded=true;
            var scr = document.createElement("script");
            var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
            scr.setAttribute('async', 'true');
            scr.type = "text/javascript";
            scr.src = host + "/j/roundtrip.js";
            ((document.getElementsByTagName('head') || [null])[0] ||
                document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
            if(oldonload){oldonload()}};
    }());
</script>
</body>
</html>