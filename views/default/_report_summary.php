<h4><strong><?php echo $this->report->name?></strong></h4>
<form id="printReportForm" name="printReportForm" method="post" action="<?php echo Yii::app()->createUrl("/Reports/default/print/".$this->report->id)?>">
	<?php echo $this->renderPartial('_report_post_data')?>
</form>
<form id="downloadForm" name="downloadForm" method="post" action="<?php echo Yii::app()->createUrl("/Reports/default/download/".$this->report->id)?>">
	<?php echo $this->renderPartial('_report_post_data')?>
</form>
<table class="reportSummary">
	<?php foreach ($this->report->inputs as $input) {
		$this->renderPartial('_input_view_'.$input->dataType->name,array('input'=>$input));
	}
	foreach ($this->report->items as $item) {
		if ($item->display) {
			$this->renderPartial('_item_'.$item->dataType->name,array('item'=>$item,'data'=>$data));
		}
	}?>
</table>
<?php foreach ($this->report->graphs as $i => $graph) {?>
	<div id="graph<?php echo $i?>" class="ReportGraph whiteBox"></div>
	<script type="text/javascript">
		var g<?php echo $i?> = new OE_BulletGraph({
			"id": "graph<?php echo $i?>",
			"initialData": <?php echo json_encode($graph->getInitialData($data))?>,
			"percentage": true,
			"width": 730,
		});
	</script>
<?php }?>
<br/><br/>
<?php if ($this->report->can_print) {?>
	<button type="submit" class="classy blue mini" id="printReport" name="print"><span class="button-span button-span-blue">Print report</span></button>
<?php }?>
<?php if ($this->report->can_download) {?>
	<button type="submit" class="classy blue mini" id="downloadReport" name="download"><span class="button-span button-span-blue">Download report</span></button>
<?php }?>
<img class="loader" style="display: none;" src="<?php echo Yii::app()->createUrl('img/ajax-loader.gif')?>" alt="loading..." />&nbsp;
<script type="text/javascript">
	$(document).ready(function() {
		$('#printReport').click(function() {
			if (!$(this).hasClass('disabled')) {
				disableButtons();
				printPDF(baseUrl+'/Reports/default/print/<?php echo $this->report->id?>',$('#printReportForm').serializeArray());
			}
			return false;
		});
		$('#downloadReport').click(function() {
			if (!$(this).hasClass('disabled')) {
				disableButtons();
				$('#downloadForm').submit();
				enableButtons();
			}
		});
	});
</script>
