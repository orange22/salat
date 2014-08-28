<div id="main"><!--main start-->
			<div id="content">
				<div class="content-box">
					<h1>Оплата и доставка</h1>
					<div class="contacts payment">
						<? foreach($delivery as $del){?>
						<div class="row">
							<h2><?=$del->title;?></h2>
							<p><?=$del->detail_text;?></p>
						</div>
						<?}?>
						
						<div class="map-holder">
							<?=Option::getOpt('deliverymap');?>
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