<h4><strong><?php echo $this->report->name?></strong></h4>
<form id="printReportForm" name="printReportForm" method="post" action="<?php echo Yii::app()->createUrl("/Reports/default/print/".$this->report->id)?>">
	<?php echo CHtml::hiddenField('reportParams',rawurlencode(json_encode($_POST)))?>
</form>
<table class="reportSummary">
	<?php foreach ($this->report->inputs as $input) {
		$this->renderPartial('_input_view_'.$input->dataType->name,array('input'=>$input));
	}
	foreach ($this->report->items as $item) {
		$this->renderPartial('_item_'.$item->dataType->name,array('item'=>$item,'data'=>$data));
	}?>
</table>
<?php foreach ($this->report->graphs as $i => $graph) {?>
	<div id="graph<?php echo $i?>" class="ReportGraph whiteBox"></div>
	<script type="text/javascript">
		var g<?php echo $i?> = new OE_BulletGraph({
			"id": "graph<?php echo $i?>",
			"uri": baseUrl+'/Reports/default/graphData?report_id=<?php echo $this->report->id?>&graph_id=<?php echo $graph->id?>',
			"initialData": <?php echo json_encode($graph->getInitialData($data))?>,
			"refreshRate": 5000,
			"percentage": true,
			"width": 730,
		});
	</script>
<?php }?>
<br/><br/>
<button type="submit" class="classy blue mini" id="printReport" name="run"><span class="button-span button-span-blue">Print report</span></button>
<img class="loader" style="display: none;" src="<?php echo Yii::app()->createUrl('img/ajax-loader.gif')?>" alt="loading..." />&nbsp;
<script type="text/javascript">
	$(document).ready(function() {
		$('#printReport').click(function() {
			if (!$(this).hasClass('disabled')) {
				disableButtons();
				$('#printReportForm').submit();
			}
			return false;
		});
	});
</script>
