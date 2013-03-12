<div class="reportInputs whiteBox">
	<h4><strong>OpenEyes Reports</strong></h4>
	<form id="reportData">
		<table class="reportInputs">
			<?php if ($this->report) {?>
				<?php foreach ($this->report->datasets as $dataset) {
					foreach ($dataset->inputs as $input) {?>
						<tr>
							<td><?php echo $input->description?>:</td>
							<td><?php $this->renderPartial('_input_field_'.$input->dataType->name,array('input'=>$input))?></td>
						</tr>
					<?php }
				}
			}?>
		</table>
	</form>
	<div id="errors">
	</div>
	<div style="margin-top: 2em;">
		<button type="submit" class="classy blue mini" id="et_run" name="run"><span class="button-span button-span-blue">Run report</span></button>
		<img class="loader" style="display: none;" src="<?php echo Yii::app()->createUrl('img/ajax-loader.gif')?>" alt="loading..." />&nbsp;
	</div>
</div>
<div class="reportSummary whiteBox" style="display: none;">
</div>
<script type="text/javascript">
	$(document).ready(function() {
		handleButton($('#et_run'),function(e) {
			$('div.reportSummary').hide();

			$.ajax({
				'type': 'POST',
				'data': $('#reportData').serialize(),
				'url': baseUrl+'/Reports/default/validate/'+OE_report_id,
				'success': function(html) {
					if (html.length == 0) {
						$('#errors').html('');
						$.ajax({
							'type': 'POST',
							'data': $('#reportData').serialize(),
							'url': baseUrl+'/Reports/default/execute/'+OE_report_id,
							'success': function(html) {
								enableButtons();
								$('div.reportSummary').html(html).show();
							}
						});
					} else {
						$('#errors').html(html);
						enableButtons();
					}
				}
			});

			e.preventDefault();
		});
	});
</script>
