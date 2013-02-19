<tr>
	<th><?php echo $input->description?>:</th>
	<td>
		<?php echo ReportInputOption::model()->findByPk(@$_REQUEST[$input->name])->name?>
	</td>
</tr>
