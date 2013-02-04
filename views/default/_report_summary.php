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
/*
		$(document).ready(function() {
			var map = [0,1,2,3,4,5,6,7,8,9,10,22,23,24,25,26,27,28,29,30,31,32];

			var i = 0;

			$('g').map(function() {
				if ($(this).attr('class') == 'tick') {
					if (inArray(i,map)) {
						$(this).remove();
					}
					i += 1;
				}
			});

			i = 0;
			$('svg').map(function() {
				if ($(this).attr('class') == 'bullet') {
					if (i %2 == 0) {
						$(this).attr('height','30');
					}
					i += 1;
				}
			});
		});
		function inArray(needle, haystack) {
				var length = haystack.length;
				for(var i = 0; i < length; i++) {
						if(haystack[i] == needle) return true;
				}
				return false;
		}
		*/
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
