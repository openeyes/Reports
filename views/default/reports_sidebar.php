<?php foreach (Report::subspecialties() as $subspecialty_id => $subspecialty) {?>
	<div class="reportsgroup curvybox">
		<h4><?php echo $subspecialty?></h4>
		<ul>
			<?php foreach (Report::model()->getAllBySpeciality($subspecialty_id) as $report) {?>
				<li<?php if ($this->report && $this->report->id == $report->id) {?> class="active"<?php }?>>
					<?php if ($this->report && $this->report->id == $report->id) {?>
						<span class="viewing"><?php echo $report->name?></span>
					<?php }else{?>
						<?php echo CHtml::link($report->name,array('/Reports/default/view/'.$report->id),array('class'=>$report->icon))?>
					<?php }?>
				</li>
			<?php }?>
		</ul>
	</div>
<?php }?>
<div class="reportsgroup curvybox">
	<h4>Orbis</h4>
	<ul>
		<li<?php if (preg_match('/reportOperationSummary/',@$_SERVER['REQUEST_URI'])) {?> class="active"<?php }?>>
			<?php if (preg_match('/reportOperationSummary/',@$_SERVER['REQUEST_URI'])) {?>
				<span class="viewing">Operation summary</span>
			<?php }else{?>
				<?php echo CHtml::link("Operation summary",array('/Reports/default/reportOperationSummary'))?>
			<?php }?>
		</li>
	</ul>
</div>
