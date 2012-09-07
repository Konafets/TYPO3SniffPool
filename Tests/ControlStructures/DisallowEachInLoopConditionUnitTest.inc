<?php
while (list($key, $val) = each($fruit)) {
	echo "$key => $val\n";
}

while (count($fruit) > 0) {
	array_pop($fruit);
}

do {
	// Some code here ...
} while (list($key, $val) = each($fruit));

do {
	array_pop($fruit);
} while (count($fruit) > 0);
?>