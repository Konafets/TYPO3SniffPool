<?php
foreach ($array as $key => $value) {
	echo $key . $value;
}

foreach ($array as $key => $value) {
	echo $value;
}

foreach ($array as $key => $value) {
	echo $key;
}

foreach ($array as $key => $_) {
	echo $key;
}

foreach ($array as $key => $_) {
	echo $key . $_;
}

foreach ($array as $key => $_) {
	echo $_;
}

foreach ($array as $value) {
	echo $value;
}

foreach ($array as $_) {
	echo $_;
}

foreach($conf['extraMarkers.'] as $marker => $value)
    $markerArray['###' . $marker . '###'] = $value;

foreach([1, 2, 3] as $val)
    // foo comment
    /* foo */
    echo $val . chr(10);