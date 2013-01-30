<div id="reports_sidebar">
	<?php foreach (Report::subspecialties() as $subspecialty_id => $subspecialty) {?>
		<div class="report open clearfix">
			<div class="report_nav">
				<div class="start_date small">
					<span class="aBtn">
						<a class="sprite showhide2" href="#">
							<span class="hide"></span>
						</a>
					</span>
				</div>
				<h4 class="title_summary">
					<?php echo $subspecialty?><br/>
					<div class="report_text"></div>
				</h4>
				<ul class="reports hide">
					<?php foreach (Report::model()->getAllBySpeciality($subspecialty_id) as $report) {?>
						<li id="reportLi<?php echo $report->id?>">
							<div class="quicklook" style="display: none; ">
								<span class="report"><?php echo $report->name?></span>
								<span class="description"><?php echo $report->description?></span>
							</div>
							<?php if ($this->report && $this->report->id == $report->id) {?>
								<div class="viewing">
							<?php }else{?>
								<a href="<?php echo Yii::app()->createUrl("/Reports/default/view/".$report->id)?>" class="show-report-details">
							<?php }?>
								<span class="type">
									<img src="<?php echo Yii::app()->createUrl('img/_elements/icons/event/small/'.$report->icon.'.png')?>" alt="op" width="19" height="19" />
								</span>
								<span class="date"><?php echo $report->name?></span>
							<?php if ($this->report && $this->report->id == $report->id) {?>
								</div>
							<?php }else{?>
								</a>
							<?php }?>
						</li>
					<?php }?>
				</ul>
			</div>
		</div>
	<?php }?>
</div>
	<script type="text/javascript">
	// basic quicklook animation... 
	
			$(document).ready(function(){
				$('.quicklook').each(function(){
					var quick = $(this);
					var iconHover = $(this).parent().find('.type');
				iconHover.hover(function(e){
					quick.fadeIn('fast');
				},function(e){
					quick.fadeOut('fast');
				});	
				});
									
				}); // ready
	
	</script>
