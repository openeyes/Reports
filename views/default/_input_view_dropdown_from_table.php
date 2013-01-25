<tr>
	<th><?php echo $input->description?>:</th>
	<td><?php echo @$_POST[$input->name] ? $input['data_type_param1']::model()->findByPk(@$_POST[$input->name])->reportDisplay : 'All'?></td>
</tr>
