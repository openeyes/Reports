<?php
$value = empty($_POST) ? $input->defaultValue : @$_POST[$input->name];

$this->widget('zii.widgets.jui.CJuiDatePicker', array(
	'name' => $input->name,
	'id' => $input->name,
	'options' => array(
		'showAnim'=>'fold',
		'dateFormat'=>Helper::NHS_DATE_FORMAT_JS
	),
	'value' => $value,
	'htmlOptions' => array('style' => "width: 95px;"),
))?>
