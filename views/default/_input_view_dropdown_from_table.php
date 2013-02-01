<?php if (@$_REQUEST[$input->name]) {?>
	<tr>
		<th><?php echo $input->description?>:</th>
		<td><?php echo @$_REQUEST[$input->name] ? $input['data_type_param1']::model()->findByPk(@$_REQUEST[$input->name])->reportDisplay : 'All'?></td>
	</tr>
<?php }?>
