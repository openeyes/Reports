<?php
foreach ($input->options as $option) {
	echo CHtml::radioButton($input->name,($option->id == $input->default_value),array('value'=>$option->id))?>
	<?php echo $option->name?>
	<br/>
<?php }?>
