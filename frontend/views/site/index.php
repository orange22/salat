<?
$cnt=0;
foreach ($categories as $cat){
if($cnt){?>
    <div class="item-head" id="<?=str_replace(' ','',strtolower(Transliteration::text($cat->title)));?>">
        <div class="container"><?=$cat->title;?></div>
    </div>
<?}?>

<div class="container">
    <div class="item-box" id="<?=str_replace(' ','',strtolower(Transliteration::text($cat->title)));?>">
        <a href="#" class="prev"></a>
        <a href="#" class="next"></a>
        <div class="gallery-holder">
            <ul class="slide-list">
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
            </ul>
        </div>
        <div class="description">
            <div class="price-col">
                <div class="price">50<span>грн</span></div>
                <div class="weight">300г</div>
            </div>
            <div class="info">
                <div class="name">Цезарь с курицей </div>
                <p>Салата Ромэн, помидоры черри, перепелиные яйца, куриная грудка, соус</p>
            </div>
        </div>
        <a href="#" class="buy-btn">Заказать</a>
        <div class="switcher-box">
            <div class="switcher">
                <ul>
                    <li class="active"><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>
            <a href="#" class="more">все <?=mb_convert_case($cat->title, MB_CASE_LOWER, "UTF-8");;?></a>
        </div>
    </div>
</div>
<?
$cnt++;
}?>
<!--<div class="item-head" id="soup">
    <div class="container">супы</div>
</div>

<div class="container">
    <div class="item-box" id="salads">
        <a href="#" class="prev"></a>
        <a href="#" class="next"></a>
        <div class="gallery-holder">
            <ul class="slide-list">
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                </li>
            </ul>
        </div>
        <div class="description">
            <div class="price-col">
                <div class="price">50<span>грн</span></div>
                <div class="weight">300г</div>
            </div>
            <div class="info">
                <div class="name">Цезарь с курицей </div>
                <p>Салата Ромэн, помидоры черри, перепелиные яйца, куриная грудка, соус</p>
            </div>
        </div>
        <a href="#" class="buy-btn">Заказать</a>
        <div class="switcher-box">
            <div class="switcher">
                <ul>
                    <li class="active"><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>
            <a href="#" class="more">все салаты</a>
        </div>
    </div>
</div>

<div class="item-head" id="soup">
    <div class="container">супы</div>
</div>

<div class="container">
    <div class="item-box">
        <a href="#" class="prev"></a>
        <a href="#" class="next"></a>
        <div class="gallery-holder">
            <ul class="slide-list">
                <li class="slide">
                    <div class="img-holder"><img src="images/img02.jpg" width="618" height="600" alt="image description" /></div>

                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img02.jpg" width="618" height="600" alt="image description" /></div>

                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img02.jpg" width="618" height="600" alt="image description" /></div>

                </li>
            </ul>
        </div>
        <div class="description">
            <div class="price-col">
                <div class="price">50<span>грн</span></div>
                <div class="weight">300г</div>
            </div>
            <div class="info">
                <div class="name">Томатный суп с мидиями </div>
                <p>Салата Ромэн, помидоры черри, перепелиные яйца, куриная грудка, соус</p>
            </div>
        </div>
        <a href="#" class="buy-btn">Заказать</a>
        <div class="switcher-box">
            <div class="switcher">
                <ul>
                    <li class="active"><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>
            <a href="#" class="more">все супы</a>
        </div>
    </div>
</div>
<div class="item-head" id="drink">
    <div class="container">напитки</div>
</div>
<div class="container">
    <div class="item-box">
        <a href="#" class="prev"></a>
        <a href="#" class="next"></a>
        <div class="gallery-holder">
            <ul class="slide-list">
                <li class="slide">
                    <div class="item">
                        <div class="img-holder"><img src="images/img08.jpg" alt="image description" /></div>
                        <div class="description">
                            <div class="price-col">
                                <div class="price">50<span>грн</span></div>
                                <div class="weight">300г</div>
                            </div>
                            <div class="info">
                                <div class="name">Апельсиновый сок</div>
                            </div>
                        </div>
                        <a href="#" class="buy-btn">Заказать</a>
                    </div>
                    <div class="item">
                        <div class="img-holder"><img src="images/img04.jpg" width="166" height="600" alt="image description" /></div>
                        <div class="description">
                            <div class="price-col">
                                <div class="price">50<span>грн</span></div>
                                <div class="weight">300г</div>
                            </div>
                            <div class="info">
                                <div class="name">Грейпфрутовый сок</div>
                            </div>
                        </div>
                        <a href="#" class="buy-btn">Заказать</a>
                    </div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                    <div class="description">
                        <div class="price-col">
                            <div class="price">50<span>грн</span></div>
                            <div class="weight">300г</div>
                        </div>
                        <div class="info">
                            <div class="name">Цезарь с курицей </div>
                            <p>Салата Ромэн, помидоры черри, перепелиные яйца, куриная грудка, соус</p>
                        </div>
                    </div>
                    <a href="#" class="buy-btn">Заказать</a>
                </li>
            </ul>
        </div>
        <div class="switcher-box">
            <div class="switcher">
                <ul>
                    <li class="active"><a href="#"></a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>
            <a href="#" class="more">все напитки</a>
        </div>
    </div>
</div>
<div class="item-head" id="desserts">
    <div class="container">десерты</div>
</div>
<div class="container">
    <div class="item-box">
        <a href="#" class="prev"></a>
        <a href="#" class="next"></a>
        <div class="gallery-holder">
            <ul class="slide-list">
                <li class="slide">
                    <div class="item">
                        <div class="img-holder"><img src="images/img05.jpg" width="359" height="600" alt="image description" /></div>
                        <div class="description">
                            <div class="price-col">
                                <div class="price">50<span>грн</span></div>
                                <div class="weight">300г</div>
                            </div>
                            <div class="info">
                                <div class="name">Тирамису</div>
                            </div>
                        </div>
                        <a href="#" class="buy-btn">Заказать</a>
                    </div>
                    <div class="item">
                        <div class="img-holder"><img src="images/img06.jpg" width="301" height="600" alt="image description" /></div>
                        <div class="description">
                            <div class="price-col">
                                <div class="price">50<span>грн</span></div>
                                <div class="weight">300г</div>
                            </div>
                            <div class="info">
                                <div class="name">Чизкейк</div>
                            </div>
                        </div>
                        <a href="#" class="buy-btn">Заказать</a>
                    </div>
                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img01.jpg" width="509" height="600" alt="image description" /></div>
                    <div class="description">
                        <div class="price-col">
                            <div class="price">50<span>грн</span></div>
                            <div class="weight">300г</div>
                        </div>
                        <div class="info">
                            <div class="name">Цезарь с курицей </div>
                            <p>Салата Ромэн, помидоры черри, перепелиные яйца, куриная грудка, соус</p>
                        </div>
                    </div>
                    <a href="#" class="buy-btn">Заказать</a>
                </li>
            </ul>
        </div>
        <div class="switcher-box">
            <div class="switcher">
                <ul>
                    <li class="active"><a href="#"></a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>
            <a href="#" class="more">все напитки</a>
        </div>
    </div>
</div>
<div class="item-head" id="yogurt">
    <div class="container">замороженный йогурт</div>
</div>
<div class="container">
    <div class="item-box">
        <a href="#" class="prev"></a>
        <a href="#" class="next"></a>
        <div class="gallery-holder">
            <ul class="slide-list">
                <li class="slide">
                    <div class="img-holder"><img src="images/img07.jpg" width="306" height="600" alt="image description" /></div>

                </li>
                <li class="slide">
                    <div class="img-holder"><img src="images/img07.jpg" width="306" height="600" alt="image description" /></div>
                </li>
            </ul>
        </div>
        <div class="description">
            <div class="price-col">
                <div class="price">29<span>грн</span></div>
                <div class="weight">100г</div>
            </div>
            <div class="info">
                <div class="name">Замороженный йогурт</div>
                <p>На выбор топпинг: малина, клубника На выбор фрукты: банан, киви</p>
            </div>
        </div>
        <a href="#" class="buy-btn">Заказать</a>
        <div class="switcher-box">
            <div class="switcher">
                <ul>
                    <li class="active"><a href="#"></a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>
            <a href="#" class="more">все напитки</a>
        </div>
    </div>
</div>
-->