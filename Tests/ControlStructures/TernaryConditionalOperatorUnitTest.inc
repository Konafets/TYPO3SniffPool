<?php
// Valid
$result = ($useComma ? ',' : '.');

// Not valid
$result = ($useComma ? ',' : $useDot ? '.' : ';');
?>
