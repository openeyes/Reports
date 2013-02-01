<?php
echo $this->report->name." Report\n";
echo "Report generated on ".date('j M Y')." at ".date('H:i')."\n\n";

foreach ($this->report->inputs as $i => $input) {
	echo $input->description.":,".$input->postedValue."\n";
}
echo "\n";

foreach (@array_pop($this->report->items)->listItems as $i => $item) {
	if ($i) echo ",";
	echo $item->name;
}
echo "\n";

foreach (array_pop($data) as $dataItem) {
	$nextLine = array();
	foreach (@array_pop($this->report->items)->listItems as $i => $item) {
		if ($i) echo ",";
		if (is_array($dataItem[$item->data_field])) {
			if (empty($dataItem[$item->data_field])) {
			} else {
				if (is_array($dataItem[$item->data_field][0])) {
					echo implode(' ',$dataItem[$item->data_field][0]);
				} else {
					echo $dataItem[$item->data_field][0];
				}
				array_shift($dataItem[$item->data_field]);
				while (!empty($dataItem[$item->data_field])) {
					$nextLine[$i][] = array_shift($dataItem[$item->data_field]);
				}
			}
		} else {
			echo $dataItem[$item->data_field];
		}
	}
	echo "\n";
	while (!empty($nextLine)) {
		foreach (@array_pop($this->report->items)->listItems as $i => $item) {
			if ($i) echo ",";
			if (isset($nextLine[$i])) {
				$nextItem = array_shift($nextLine[$i]);
				echo is_array($nextItem) ? implode(' ',$nextItem) : $nextItem;
				if (empty($nextLine[$i])) {
					unset($nextLine[$i]);
				}
			}
		}
		echo "\n";
	}
}
