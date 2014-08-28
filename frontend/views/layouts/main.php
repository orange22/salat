<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Салатник</title>
    <link rel="stylesheet" href="css/all.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery.scrollTo.js" type="text/javascript"></script>
    <script src="js/jquery.nav.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.main.js"></script>
    <!--[if lte IE 8]><script type="text/javascript" src="js/ie.js"></script><![endif]-->
</head>
<body>
<div id="wrapper">
<div id="header">
    <div class="container clear">
        <strong class="logo"><a href="#">Салатник</a></strong>
        <div class="header-frame">
            <nav id="nav">
                <ul class="nav">
                    <?
                    $cnt=0;
                    foreach($this->categories as $cat){?>
                    <li<?=(!$cnt)?' class="active"':'';?>><a href="#<?=str_replace(' ','',strtolower(Transliteration::text($cat->title)));?>"><?=$cat->title;?></a></li>
                    <?
                    $cnt++;
                    }?>
                    <!--<li><a href="#soup">супы</a></li>
                    <li><a href="#drink">напитки</a></li>
                    <li><a href="#desserts">десерты</a></li>
                    <li><a href="#yogurt">Замороженный йогурт</a></li>-->
                    <li><a href="#map">карта доставки</a></li>
                    <li class="basket"><a href="#">корзина</a></li>
                </ul>
            </nav>
            <div class="phone">(044) 123-45-67</div>
        </div>
    </div>
</div>
<div id="main">
    <?=$content;?>
    <div class="item-head" id="map">
        <div class="container">карта доставки</div>
    </div>
    <div class="map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2541.0889936984854!2d30.522504050950385!3d50.43944299769924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sua!4v1409053248319" width="100%" height="425" frameborder="0" style="border:0"></iframe>
    </div>
</div>
<footer id="footer">
    <div class="container clear footer-frame">
        <strong class="footer-logo"><a href="#">Салатник</a></strong>
        <div class="contacts">
            <div class="col">Киев, ул. Крещатик, 1а <br>работаем 10-22:00</div>
            <div class="col">Киев, ул. Крещатик, 1а <br>работаем 10-22:00</div>
            <div class="col">Киев, ул. Крещатик, 1а <br>работаем 10-22:00</div>
        </div>
        <ul class="social-list">
            <li><a href="#"><img src="images/ico02.png" width="17" height="17" alt="image description" /></a></li>
            <li><a href="#"><img src="images/ico03.png" width="17" height="17" alt="image description" /></a></li>
        </ul>
    </div>
</footer>
</body>
</html>
