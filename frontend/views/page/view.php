		<div id="main"><!--main start-->
			<div id="content">
				<div class="content-box">
					<h1><?=$page['title'];?></h1>
					<? if(isset($page->image)){?>
					<div class="visual-box" style="margin-bottom:10px;">
						<?=$page->image->asHtmlImage($page->title);?>
					</div>
					<?}?>
					<div class="content-block">
							<p><?=$page['detail_text'];?></p>
					</div>
				</div>
			</div>
			<?=$this->renderWidgets($page->id);?>
		<div class="see-menu btn-holder center">
			<a href="/#top" class="green-btn">
				<span><?=Yii::t('frontend', 'Actualdish');?></span>
			</a>
		</div>
        </div><!--main end-->