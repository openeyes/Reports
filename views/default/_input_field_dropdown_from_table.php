<?php
echo CHtml::dropDownList($input->name,@$_POST[$input->name],$input['data_type_param1']::model()->{$input->data_type_param2}(),array('empty'=>'--- Please select ---'));
?>
