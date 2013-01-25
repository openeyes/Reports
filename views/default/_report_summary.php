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
<br/>
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
