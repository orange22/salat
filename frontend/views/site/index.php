<div class="promo-gallery-holder">
			<ul class="promo-gallery">
				<?
				$cnt=1;
				foreach($teasers as $teaser){
				if($teaser->link)
				$teaser->link=str_replace(array('http://','https://'), '', $teaser->link);
				if($teaser->video){
				?>
				<li<?=($cnt==1)?' class="active"':'';?>><?=$teaser->video;?></li>
				<?}else{?>
				<li<?=($cnt==1)?' class="active"':'';?>><?=(strlen(trim($teaser->link))>0)?'<a href="http://'.$teaser->link.'">':'';?><?=$teaser->image->asHtmlImage();?><?=(strlen(trim($teaser->link))>0)?'</a>':'';?></li>
				<?}
				$cnt++;
				}?>
			</ul>
			<div class="switcher">
				<ul>
					<?
					$cnt=0;
					 foreach($teasers as $teaser){?>
					<li><a<?=(!$cnt)?' class="active"':'';?> href="#"></a></li>
					<?
					$cnt++;
					}?>
				</ul>
			</div>
		</div>
		<div id="main"><!--main start-->
			<!-- start catalog-list -->
			<?
			$cnt=0;
			foreach($dishtypes as $dishtype){
			if(count($dishtype->dishes)<4)
			continue;
			if($cnt>1)
			$cnt=0;
			?>
			<ul class="catalog-list"><?
				$dcnt=0;
				foreach($dishtype->dishes as $dish){
				if($dcnt>3)
				break;
				if(!$dcnt){
				?><li class="double <?=(!$cnt)?' left':' right';?>">
                    <? if($dish->new){?>
                    <span class="news-label">Новинка</span>
                    <?}?>
					<div class="img-holder">
						<a href="<?=$dish->getUrl();?>">
						<? if(isset($dish->dishImages[1]->image)){?>
						<?=$dish->dishImages[1]->image->asHtmlImage($dish->title);?>
						<!--<img src="images/img35.jpg" width="460" height="421" alt="image description" />-->
						<?}?>
						<span class="mask">&nbsp;</span>
						</a>
                        <? if(Option::getOpt('buy')){?>
                            <a data-cat="<?=$dish->dishtype_id;?>" rel="<?=(isset($dish->portions[0]->value))?$dish->portions[0]->value:1;?>" href="/cart/add/<?=$dish->id;?>/?q=<?=(isset($dish->portions[0]->value))?$dish->portions[0]->value:1;?>" class="to-cart callbuy-popup"></a>
                        <?}?>
                        <a href="<?=$dish->getUrl();?>" class="play"></a>
					</div>
					<div class="info-row">
						<span class="bottom-deco">&nbsp;</span>
						<? if(isset($dishtype->dishtypeimage)){?>
						<span class="ico-frame"><img src="/<?=$dishtype->dishtypeimage->path;?>/<?=$dishtype->dishtypeimage->file;?>" width="31" height="29" alt="image description" /></span>
						<?}?>
						<div class="info-frame">
							<div class="title"><a href="<?=$dish->getUrl();?>"><?=$dish->title;?></a></div>
							<span class="price"><strong><?=$dish->price;?></strong> грн.</span>
							<? if($dish->weight>0){?>
							<span class="weight-text"><strong><?=$dish->weight*1000;?></strong> грамм</span>
							<?}?>
						</div>
					</div>
				</li><?}else{?><!--
				 --><li>
                    <? if($dish->new){?>
                        <span class="news-label">Новинка</span>
                    <?}?>
					<div class="img-holder">
						<a href="<?=$dish->getUrl();?>">
						<? if(isset($dish->dishImages[1]->image)){?>
						<img src="/<?=$dish->dishImages[1]->image->path;?>/<?=$dish->dishImages[1]->image->file;?>" width="220" alt="<?=$dish->title;?>" />
						<?}?>
						<span class="mask">&nbsp;</span>
						</a>
                        <? if(Option::getOpt('buy')){?>
                        <a data-cat="<?=$dish->dishtype_id;?>" rel="<?=(isset($dish->portions[0]->value))?$dish->portions[0]->value:1;?>" href="/cart/add/<?=$dish->id;?>/?q=<?=(isset($dish->portions[0]->value))?$dish->portions[0]->value:1;?>" class="to-cart callbuy-popup"></a>
                        <?}?>
                        <a href="<?=$dish->getUrl();?>" class="play"></a>
					</div>
					<div class="info-row">
						<span class="bottom-deco">&nbsp;</span>
						<div class="info-frame">
							<div class="title">
								<a href="<?=$dish->getUrl();?>">
									<?
									if(strlen($dish->title)>40){
									$dish->title=mb_substr(strip_tags($dish->title), 0, 37, 'UTF-8')."...";}
									echo $dish->title;
									?>
								</a>
							</div>
							<span class="price"><strong><?=$dish->price;?></strong> грн.</span>
							<? if($dish->weight>0){?>
							<span class="weight-text"><strong><?=$dish->weight*1000;?></strong> грамм</span>
							<?}?>
						</div>
					</div>
				</li><?} if($cnt && !$dcnt){?><!-- 
				 --><li class="category">
                    <a href="/dish/category/<?=$dishtype->id;?>/">
					<span class="visual">
						<span class="bulb">
							<img src="/images/bulb01.png" width="210" height="154" alt="image description" />
						</span>
						<span class="ico">
							<? if(isset($dishtype->dishtypeimage2)){?>
                                <?=$dishtype->dishtypeimage2->asHtmlImage($dishtype->title);?>
                            <?}?>
						</span>
						<span class="category-name"><?=$dishtype->title;?></span>
					</span>
                        <span class="text"><strong><?=count($dishtype->dishes);?></strong> наборов</span>
                        <span class="bg"><img src="/images/category-bg.png" alt="image description" /></span>
                    </a>
                </li><?}?><?
				$dcnt++;
				}?><? if(!$cnt){?><!-- 
				 --><li class="category">
                    <a href="/dish/category/<?=$dishtype->id;?>/">
					<span class="visual">
						<span class="bulb">
							<img src="/images/bulb01.png" width="210" height="154" alt="image description" />
						</span>
						<span class="ico">
							<? if(isset($dishtype->dishtypeimage2)){?>
                                <?=$dishtype->dishtypeimage2->asHtmlImage($dishtype->title);?>
                            <?}?>
						</span>
						<span class="category-name"><?=$dishtype->title;?></span>
					</span>
                        <span class="text"><strong><?=count($dishtype->dishes);?></strong> наборов</span>
                        <span class="bg"><img src="/images/category-bg.png" alt="image description" /></span>
                    </a>
                </li><?}?>
			</ul>
			<?
			$cnt++;
			}?>
                <? if(isset($replies)){?>
                <div class="replies">
                    <div class="replies-header">
                        <a href="/reply/">&nbsp;</a>
                    </div>
                    <ul class="reply-list">
                        <?
                        $cnt=1;
                        foreach($replies as $reply){
                        ?>
                        <li<?=($cnt==4)?' class="reply-last"':'';?>>
                            <div class="reply-image"><?=$reply->image->asHtmlImage($reply->login);?></div>
                            <div class="reply-desc"><span class="replier">@<?=$reply->login;?></span><span class="reply-test"><?=$reply->detail_text;?></span></div>
                        </li>
                        <?
                            $cnt++;
                            if($cnt==5)
                                $cnt=1;
                        }?>
                    </ul>
                </div>
            <?}?>
		</div><!--main end-->