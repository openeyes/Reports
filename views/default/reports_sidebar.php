<?php foreach (Report::subspecialties() as $subspecialty_id => $subspecialty) {?>
	<div class="box admin">
		<h2><?php echo $subspecialty?></h2>
		<ul class="navigation admin">
			<?php foreach (Report::model()->getAllBySpeciality($subspecialty_id) as $report) {?>
				<li>
					<?php echo CHtml::link($report->name,array('/Reports/default/view/'.$report->id),array('class'=>$report->icon.(($this->report && $this->report->id == $report->id)?' selected':'')))?>
				</li>
			<?php }?>
		</ul>
	</div>
<?php }?>
