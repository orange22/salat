		<div id="main"><!--main start-->
			<div id="content">
				<div class="content-box">
					<h1><?=$title;?></h1>
					<div class="content-block">
							<p><?=$text;?></p>
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