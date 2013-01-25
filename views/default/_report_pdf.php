<style>
	th { text-align: left; font-weight: bold; }
	td { margin-top: 10px; margin-right: 10px; }
</style>
<h1><?php echo $this->report->name?></h1>
<h3>Report generated on <?php echo date('j M Y')?> at <?php echo date('H:i')?></h3>
<div style="height: 2em;"></div>
<table class="reportSummary">
	<?php foreach ($this->report->inputs as $input) {
		$this->renderPartial('_input_view_'.$input->dataType->name,array('input'=>$input));
	}?>
	<div style="height: 2em;"></div>
	<?php foreach ($this->report->items as $item) {
		$this->renderPartial('_item_'.$item->dataType->name,array('item'=>$item,'data'=>$data));
	}?>
</table>
