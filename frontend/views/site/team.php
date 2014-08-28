<div id="main"><!--main start-->
			<div id="content">
				<div class="content-box">
					<h1>Команда</h1>
					<div class="team-list-holder">
						<ul class="team-list">
							<? foreach($team as $cook){?>
							<li>
								<div class="img-holder round">
									<?=($cook->image)?$cook->image->asHtmlImage():null;?>
								</div>
								<div class="text">
									<div class="title"><?=$cook->name;?></div>
									<p><em><?=$cook->detail_text;?></em></p>
								</div>
							</li>
							<?}?>
						</ul>
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