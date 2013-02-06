<tr>
	<td><?php echo $item->subtitle?>:</td>
	<td>
		<?php
		$i=0;
		if (isset($data[$item->data_field])) {
			foreach ($data[$item->data_field] as $key => $values) {?>
				<?php if ($i>0) echo '<br/>'?>
				<?php echo $key?>: <?php echo $values['number']?> (<?php echo $values['percentage']?>%)
			<?php $i++;}
		}?>
	</td>
</tr>
