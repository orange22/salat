<div id="main"><!--main start-->
			<div id="content">
				<div class="content-box">
					<h1>Частые вопросы</h1>
					<div class="accordion">
						<? foreach($faq as $f){?>
						<div class="item">
							<div class="heading"><?=$f->title;?></div>
							<div class="expanded">
								<p><?=$f->answer;?></p>
							</div>
						</div>
						<?}?>
						
					</div>
				</div>
			</div>
			<?=$this->renderWidgets(6);?>

		<div class="see-menu btn-holder center">
			<a href="/#top" class="green-btn">
				<span><?=Yii::t('frontend', 'Actualdish');?></span>
			</a>
		</div>
</div><!--main end-->