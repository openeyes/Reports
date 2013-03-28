<h3 class="georgia">Operation summary</h3>
<?php /*<form id="printReportForm" name="printReportForm" method="post" action="<?php echo Yii::app()->createUrl("/Reports/default/print/".$this->report->id)?>">
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
*/?>
<table class="grid reduceheight operationSummary">
	<tbody>
		<tr>
			<th class="blank"></th>
			<?php foreach ($cells['header'] as $site => $siteData) {?>
				<th colspan="12" class="<?php echo str_replace(' ','_',$site)?>"><?php echo $site?></th>
			<?php }?>
		</tr>
		<tr>
			<th class="blank"></th>
			<?php foreach ($cells['header'] as $site => $siteData) {
				foreach ($siteData as $column => $columnData) {?>
					<th colspan="3" class="<?php echo str_replace(' ','_',$site)?>"><?php echo $column?></th>
				<?php }?>
			<?php }?>
		</tr>
		<tr>
			<th class="blank"></th>
			<?php foreach ($cells['header'] as $site => $siteData) {
				foreach ($siteData as $column => $columnData) {
					foreach ($columnData as $column2) {?>
						<th<?php if (!in_array($column2,array('M','F'))) {?> class = "total"<?php }?>><?php echo $column2?></th>
					<?php }
				}
			}?>
			<th class="grandtotal">TOTAL</th>
		</tr>
		<?php
		$i=0;
		foreach ($cells['data'] as $subspecialty => $subspecialtyData) {?>
			<tr class="<?php if ($i %2==0) {?>even<?php }else{?>odd<?php }?>">
				<td class="subspecialty"><?php echo $subspecialty?></td>
				<?php foreach ($subspecialtyData as $site => $siteData) {
					foreach ($siteData as $values) {
						foreach ($values as $key => $value) {?>
							<td<?php if (!in_array($key,array('M','F'))) {?> class="total"<?php }?>><?php echo $value?></td>
						<?php }
					}
				}?>
				<td class="grandtotal"><?php echo $cells['Subspecialties'][$subspecialty]?></td>
			</tr>
		<?php $i++; }?>
		<tr>
			<td class="subspecialty total">TOTAL</td>
			<?php foreach ($cells['Total'] as $n) {?>
				<td class="total"><?php echo $n?></td>
			<?php }?>
			<td class="grandtotal"><?php echo $grand_total?></td>
		</tr>
	</tbody>
</table>
