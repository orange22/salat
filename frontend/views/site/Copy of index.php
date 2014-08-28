   
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
				<li<?=($cnt==1)?' class="active"':'';?>><?=($teaser->link)?'<a href="http://'.$teaser->link.'">':'';?><?=$teaser->image->asHtmlImage();?><?=($teaser->link)?'</a>':'';?></li>
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
		    <?
		    if($menutime=Option::getDate('menutime')){
		    ?>
		    <div class="time-left">
		        <div class="left-text"><span>Данное меню действует до </span><strong><?=$menutime['date'];?></strong></div>
		        <? if($menutime['days']>0){?>
		          <div class="left-time"><span>Осталось</span><strong><?=$menutime['days'];?></strong><span>&nbsp;дн.</span><strong><?=$menutime['hours'];?></strong><span>&nbsp;ч.</span></div>
		        <?}else{?>
		          <div class="left-time"><span>Осталось</span><strong><?=$menutime['hours'];?></strong><span>&nbsp;ч.</span> <strong><?=$menutime['minutes'];?></strong><span>&nbsp;мин.</span></div>
		        <?}?>
		          <a href="#" class="call-popup white-q" rel="<?=Option::getOpt('timerpopup');?>"></a>
		    </div>
		    <?}?>
			<ul name="top" id="top" class="recipe-list"><!--recipe-list start-->
				<? 
				$lcnt=0;
				foreach($topdishes as $tdish){
				$tdish['price']=explode('.',$tdish['price']);
			    if($lcnt==1){
			    ?>
                <li class="dishbanner">
                    <img src="/images/2dish-banner.png"/>
                </li>    
                <?}
			    ?>
				<li>
					<div class="recipe-gallery-holder">
					    <div class="recipe-gallery">
							<ul>
							<? 
							if($tdish->dishImages){
							foreach($tdish->dishImages as $image){?>
								<li><a href="<?=$tdish->getUrl();?>"><?=$image->image->asHtmlImage($tdish->title);?></a></li>
							<?}}else{?>
								<li><a href="<?=$tdish->getUrl();?>"><img width="455" height="390" src="/images/zaglush.jpg" alt="Омлет"></a></li>
							<?}?>
							</ul>
						</div>
						<div class="switcher">
							<div class="switcher-box">
								<ul>
									<li><a class="active" href="#"></a></li>
									<li><a href="#"></a></li>
									<li><a href="#"></a></li>
									<li><a href="#"></a></li>
								</ul>
							</div>
						</div>
						<i class="dish4-<?=$tdish['persons'];?>"></i>
					</div>
					<div class="recipe-frame">
						<div class="head">
							<div class="btn-holder">
								<a href="<?=$tdish->getUrl();?>" class="green-btn">
									<span>Смотреть</span>
								</a>
							</div>
							
							<ul class="ingredients-list">
								<? if(isset($tdish->courses)){
									$cnt=0;
									foreach($tdish->courses as $course){
										if(isset($course->coursetype->coursetypeimage)){?>
											<li><?=$course->coursetype->coursetypeimage->asHtmlImage($course->coursetype->title);?></li>
										<?
										if($cnt==1)
										break;
										$cnt++;
										}
									}
								}?>								
							</ul>
						</div>
						<div class="text-box">
							<h3><a href="<?=$tdish->getUrl();?>"><?=$tdish->title;?></a></h3>
							<p><?=$tdish->detail_text;?></p>
						</div>
						<div class="bottom-tools">
							<div class="price">
								<div class="num"><?=$tdish['price'][0];?></div>
								<div class="currency">
									<span><?=$tdish['price'][1];?></span>
									<i>грн</i>
								</div>
							</div>
							<? if($tdish['weight']>0){?>
							<div class="weight-arrow"></div>
							<div class="weight">
                                <span class="bob"></span>
                                
                                <i><?=($tdish['weight']>1)?str_replace('.',',',trim($tdish['weight'],0)):str_replace('.',',',rtrim($tdish['weight'],'0'));?></i> кг
                            </div>
                            <?}?>
                            <?=$this->renderShare($tdish->getUrl(),$tdish->title);?>
						</div>
					</div>
					<div style="clear:both;"></div>
				</li>
				<?
                $lcnt++;
                }?>
			</ul><!--recipe-list end-->
			<div class="delivery-banner"><a href="#" class="call-popup red-q" rel="<?=Option::getOpt('deliverpopup');?>"></a></div>
		</div><!--main end-->