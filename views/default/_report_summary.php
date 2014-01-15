<h3 class="georgia"><?php echo $this->report->name?></h3>
<form id="printReportForm" name="printReportForm" method="post" action="<?php echo Yii::app()->createUrl("/Reports/default/print/".$this->report->id)?>">
	<?php echo $this->renderPartial('_report_post_data')?>
</form>
<form id="downloadForm" name="downloadForm" method="post" action="<?php echo Yii::app()->createUrl("/Reports/default/download/".$this->report->id)?>">
	<?php echo $this->renderPartial('_report_post_data')?>
</form>
<table class="subtle grey smalltext reduceheight">
	<colgroup>
		<col width="35%"></col>
	</colgroup>
	<thead>
		<tr>
			<th colspan="2">Search Details:</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->report->datasets as $dataset) {
			foreach ($dataset->inputs as $input) {
				$this->renderPartial('_input_view_'.$input->dataType->name,array('input'=>$input));
			}
		}?>
	</tbody>
</table>
<table class="grid reduceheight">
	<tbody>
	<?php
	foreach ($this->report->datasets as $dataset) {
		foreach ($dataset->displayItems as $item) {
			$this->renderPartial('_item_'.$item->dataType->name,array('item'=>$item,'data'=>$data));
		}
	}?>
	</tbody>
</table>
<?php foreach ($this->report->graphs as $i => $graph) {?>
	<div id="graph<?php echo $i?>" class="ReportGraph whiteBox"></div>
	<script type="text/javascript">
		var g<?php echo $i?> = new OE_BulletGraph({
			"id": "graph<?php echo $i?>",
			"initialData": <?php echo json_encode($graph->getInitialData($data))?>,
			"percentage": true,
			"width": 630,
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
<img class="loader" style="display: none;" src="<?php echo Yii::app()->assetManager->createUrl('img/ajax-loader.gif')?>" alt="loading..." />&nbsp;
<script type="text/javascript">
	$(document).ready(function() {
		$('#printReport').click(function() {
			if (!$(this).hasClass('disabled')) {
				printPDF(baseUrl+'/Reports/default/print/<?php echo $this->report->id?>',$('#printReportForm').serializeArray());
			}
			return false;
		});
		handleButton($('#downloadReport'),function() {
			disableButtons();
			$('#downloadForm').submit();
			enableButtons();
		});
	});
</script>
