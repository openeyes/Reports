<tr>
	<th><?php echo $input->description?>:</th>
	<td>
		<?php foreach (@$_REQUEST[$input->name] as $i => $multiItem) {
			if ($i >0) {
				echo "<br/>";
			}
			echo $multiItem;
		}?>
	</td>
</tr>
