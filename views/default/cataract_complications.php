<div class="whiteBox">
	<?php $count=0; foreach ($data['cataract_operations']['surgeons'] as $surgeon => $data) {?>
		<div style="display: inline-block; width: 130px; height: 160px;">
			<center>
				<span class="surgeonGauge" id="gauge<?php echo $count?>" data-surgeon="<?php echo $surgeon?>" data-pc="<?php echo $data['pc']?>"></span><br/>
				<span class="surgeonGaugeLabel"><?php echo $surgeon?><br/><?php echo $data['count'].'/'.$data['total']?>, <?php echo $data['pc']?>%</span>
			</center>
		</div>
	<?php $count++; }?>
</div>
<script type="text/javascript">
	var surgeonGauges = [];

	$('span.surgeonGauge').map(function() {
		var surgeonName = $(this).attr('data-surgeon');

		var config = { size:120, label: 'fuckup rate', minorTicks: 5 };
		config.redZones = [];
		config.redZones.push({ from: 80, to: 100 });
		config.yellowZones = [];
		config.yellowZones.push({ from: 40, to: 80 });
		surgeonGauges[surgeonName] = new Gauge($(this).attr('id'),config);
		surgeonGauges[surgeonName].render();
		surgeonGauges[surgeonName].redraw($(this).attr('data-pc'));
	});
</script>
