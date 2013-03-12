<tr>
	<th><?php echo $input->description?>:</th>
	<td>
		<?php if (empty($_REQUEST['principal']) && empty($_REQUEST['secondary'])) {?>
			None
		<?php }else{
			if (isset($_REQUEST['principal'])) {
				foreach ($_REQUEST['principal'] as $i => $disorder_id) {?>
					<?php if ($i>0) echo '<br/>'?>
					<?php echo Disorder::model()->findByPk($disorder_id)->term?> (principal)
				<?php }
			}
			if (isset($_REQUEST['secondary'])) {
				foreach ($_REQUEST['secondary'] as $i => $disorder_id) {?>
					<?php if ($i>0 || !empty($_REQUEST['principal'])) echo "<br/>"?>
					<?php echo Disorder::model()->findByPk($disorder_id)->term?> (secondary)
				<?php }
			}
		}?>
	</td>
</tr>
