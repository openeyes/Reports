<tr>
	<th><?php echo $input->description?>:</th>
	<td>
		<?php if (empty($_POST['selected_diagnoses'])) {?>
			None
		<?php }else{?>
			<?php foreach ($_POST['selected_diagnoses'] as $i => $disorder_id) {?>
				<?php if ($i>0) echo '<br/>'?>
				<?php echo Disorder::model()->findByPk($disorder_id)->term?>
				<?php if (empty($_POST['principal']) || !in_array($disorder_id,$_POST['principal'])) {?> (secondary)<?php }else{?> (principal)<?php }?>
			<?php }?>
		<?php }?>
	</td>
</tr>
