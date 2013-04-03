<div class="report curvybox white">
	<div class="reportInputs">
		<h3 class="georgia">Operation summary</h4>
		<form id="reportData">
			<table class="subtle white nodivider showrows valignmiddle">
				<colgroup>
					<col width="35%"></col>
				</colgroup>
				<tbody>
					<tr>
						<td>Event type:</td>
						<td><?php echo CHtml::dropDownList('event_type_id','',EventType::getOrbisList(),array('empty'=>'- Please select -'))?></td>
					</tr>
					<tr>
						<td>Date from:</td>
						<td>
							<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
								'name' => 'date_from',
								'id' => 'date_from',
								'options' => array(
									'showAnim'=>'fold',
									'dateFormat'=>Helper::NHS_DATE_FORMAT_JS
								),
								'value' => date('j M ').(date('Y')-1),
								'htmlOptions' => array('style' => "width: 95px;")
							))?>
						</td>
					</tr>
					<tr>
						<td>Date to:</td>
						<td>
							<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
								'name' => 'date_to',
								'id' => 'date_to',
								'options' => array(
									'showAnim'=>'fold',
									'dateFormat'=>Helper::NHS_DATE_FORMAT_JS
								),
								'value' => date('j M Y'),
								'htmlOptions' => array('style' => "width: 95px;")
							))?>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<div id="errors">
		</div>
		<div style="margin-top: 2em;">
			<button type="submit" class="classy blue mini" id="et_run" name="run"><span class="button-span button-span-blue">Run report</span></button>
			<img class="loader" style="display: none;" src="<?php echo Yii::app()->createUrl('img/ajax-loader.gif')?>" alt="loading..." />&nbsp;
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
				'data': $('#reportData').serialize(),
				'url': baseUrl+'/Reports/default/reportOperationSummaryValidate',
				'success': function(html) {
					if (html.length == 0) {
						$('#errors').html('');
						$.ajax({
							'type': 'POST',
							'data': $('#reportData').serialize(),
							'url': baseUrl+'/Reports/default/reportOperationSummaryExecute',
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
