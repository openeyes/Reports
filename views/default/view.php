<pre>
<?php foreach ($data as $key => $value) {
	if (is_array($value)) {
		echo "$key:\n";
		foreach ($value as $key2 => $value2) {
			if (is_array($value2)) {
				echo " - $key2:\n";
				foreach ($value2 as $key3 => $value3) {
					if (is_array($value3)) {
						if (isset($value3['count'])) {
							echo " - - $key3: ".$value3['count'].'/'.$value3['total'].' ('.$value3['pc']."%)\n";
						} else {
							echo " - - $key3\n";
							foreach ($value3 as $key4 => $value4) {
								echo " - - - $key4: $value4\n";
							}
						}
					} else {
						echo " - - $key3: $value3\n";
					}
				}
			} else {
				echo " - $key2: $value2\n";
			}
		}
	} else {
		echo "$key: $value\n";
	}
}?>
</pre>
