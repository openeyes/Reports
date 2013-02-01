<?php echo CHtml::hiddenField($input->name,0)?>
<?php echo CHtml::checkBox($input->name,!empty($_POST) ? @$_POST[$input->name] : $input->default_value)?>
