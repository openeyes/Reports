<div class="whiteBox forClinicians" style="width: 69em;">
	<div class="data_row">
		<table class="subtleWhite">
			<thead>
				<tr>
					<th style="width: 400px;">Diagnosis</th>
					<th>Principal</th>
					<th>Edit</th>
				</tr>
			</thead>
			<tbody id="Reports_diagnoses">
			</tbody>
		</table>
	</div>
</div>

<div id="selected_diagnoses">
</div>

<?php $this->widget('application.widgets.DiagnosisSelection', array(
		'field' => 'disorder_id',
		'options' => $this->getCommonOphthalmicDisorders(),
		'layout' => 'minimal',
		'callback' => 'Reports_AddDiagnosis',
))?>
