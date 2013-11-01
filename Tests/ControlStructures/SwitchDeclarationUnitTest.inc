<?php

// Valid SWITCH statement.
switch ($something) {
	case '1':
		$case = '1';
		$content .= $this->extendedUse();
		break;
	case '2':
		$case = '2';
		// Fall through
	case '3':
		$case = '5';
		break;
	case '4':
		$case = '4';
		break;
	default:
		$case = null;
}

// Alignment wrong.
switch ($something) {
	case '1':
		$case = '1';
		return '1';
case '2':
	$case = '2';
	// Fall through
	case '3':
		$case = '5';
	break;
case '4':
	$case = '4';
break;
	default:
		$case = null;
}

// Closing brace wrong.
switch ($something) {
	case '1':
		$case = '1';
		break;
	default:
		$case = null;
	}

// No default case
switch ($something) {
	case '1':
		$case = '1';
		break;
}

// Default case contain break statement
switch ($something) {
	case '1':
		$case = '1';
		break;
	default:
		$case = null;
		break;
}

// No comment inside case 2 to mark the fall through as purposely
// Only one break per case allowed
switch ($something) {
	case '1':
		$case = '1';
		$content .= $this->extendedUse();
		break;
	case '2':
		$case = '2';
	case '3':
		$case = '5';
		break;
		break;
	case '4':
		$case = '4';
		break;
	default:
		$case = null;
}

// Valid SWITCH statement.
switch ($something) {
	case '1':
		$case = '1';
		$content .= $this->extendedUse();
		break;
	case '2':
		$case = '2';
		// Fall through
	case '3':
		$case = '5';
		break;
	default:
		$case = null;
	case '4':
		$case = '4';
		break;
}
?>
