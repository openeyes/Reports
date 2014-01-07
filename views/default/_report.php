<div class="report curvybox white">
	<div class="reportInputs">
		<h3 class="georgia"><?php echo $this->report->name?></h4>
		<form id="reportData">
			<table class="subtle white nodivider showrows valignmiddle">
				<colgroup>
					<col width="35%"></col>
				</colgroup>
				<tbody>
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
				</tbody>
			</table>
		</form>
		<div id="errors">
		</div>
		<div style="margin-top: 2em;">
			<button type="submit" class="classy blue mini" id="et_run" name="run"><span class="button-span button-span-blue">Run report</span></button>
			<img class="loader" style="display: none;" src="<?php echo Yii::app()->assetManager->createUrl('img/ajax-loader.gif')?>" alt="loading..." />&nbsp;
		</div>
	</div>
</div>
<div class="reportSummary report curvybox white blueborder" style="display: none;">
</div>
<script type="text/javascript">
	$(document).ready(function() {
		handleButton($('#et_run'),function(e) {
			$('div.reportSummary').hide();

			$.ajax({
				'type': 'POST',
				'data': $('#reportData').serialize()+"&YII_CSRF_TOKEN="+YII_CSRF_TOKEN,
				'url': baseUrl+'/Reports/default/validate/'+OE_report_id,
				'success': function(html) {
					if (html.length == 0) {
						$('#errors').html('');
						$.ajax({
							'type': 'POST',
							'data': $('#reportData').serialize()+"&YII_CSRF_TOKEN="+YII_CSRF_TOKEN,
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
