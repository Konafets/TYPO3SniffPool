<?php
// Wrong:
switch ($variable) {
	case 1:
			// do something
		break;
		break;
	case 2:
			// do something
		break;
}

// Wrong:
switch ($variable) {
	case 1:
			// do something
		break;
		break;
		break;
}

// Wrong:
switch($foo){
	case 1:
		if($bar == $baz) {
				// Some code
			break;
		}
		break;
	default:
}

// Correct:
if ($foo) {
	$var = $int;
		// huselpusel
	break;
}

?>