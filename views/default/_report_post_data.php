<?php foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		echo CHtml::hiddenField($key,$value);
	} else {
		foreach ($value as $value2) {
			echo CHtml::hiddenField($key.'[]',$value2);
		}
	}
}?>
