<tr>
	<td><?php echo $item->subtitle?>:</td>
	<td>
		<?php foreach ($data[$item->data_field] as $key => $values) {?>
			<?php echo $key?>: <?php echo $values['number']?> (<?php echo $values['percentage']?>%)<br/>
		<?php }?>
	</td>
</tr>
