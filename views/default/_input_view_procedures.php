<tr>
	<th><?php echo $input->description?>:</th>
	<td>
		<?php if (empty($_REQUEST['procedure'])) {?>
			None
		<?php }else{
			if (isset($_REQUEST['procedure'])) {
				foreach ($_REQUEST['procedure'] as $i => $procedure_id) {?>
					<?php if ($i>0) echo '<br/>'?>
					<?php echo Procedure::model()->findByPk($procedure_id)->term?>
				<?php }
			}
		}?>
	</td>
</tr>
