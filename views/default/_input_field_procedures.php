<div class="whiteBox forClinicians" style="width: 33.5em;">
	<div class="data_row">
		<table class="subtleWhite">
			<thead>
				<tr>
					<th style="width: 400px;">Procedure</th>
					<th>Edit</th>
				</tr>
			</thead>
			<tbody id="Reports_procedures">
			</tbody>
		</table>
	</div>
</div>

<div id="selected_procedures">
</div>

<?php $this->widget('application.widgets.ProcedureSelection',array(
	'field' => 'procedure_id',
	'durations' => true,
	'layout' => 'minimal',
	'callback' => 'Reports_AddProcedure',
))?>
