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
		$case = NULL;
}

// Alignment wrong.
switch ($something) {
	case '1':
		$case = '1';
		return '1';
case '2':
	$case = '2';
	break;
	case '3':
		$case = '5';
	break;
case '4':
	$case = '4';
break;
	default:
		$case = NULL;
}

// Closing brace wrong.
switch ($something) {
	case '1':
		$case = '1';
		break;
	default:
		$case = NULL;
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
		$case = NULL;
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
		$case = NULL;
}

// Default statement is not the last one in the switch
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
		$case = NULL;
	case '4':
		$case = '4';
		break;
}

// First case statement passes control directly to second case statement
switch ($something) {
	case '1':
	case '2':
		// Fall through
	case '3':
		$case = '5';
		break;
	case '4':
		$case = '4';
		break;
	default:
		$case = NULL;
}

// Blank line before the break statement
// Blank line after default statement
switch ($something) {
	case '1':
		$case = '1';
		$content .= $this->extendedUse();

		break;
	case '2':
		$case = '2';
		break;
	case '3':
		$case = '5';
		break;
	case '4':

		$case = '4';
		break;
	default:
		$case = NULL;
}
?>
