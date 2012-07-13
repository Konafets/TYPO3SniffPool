<?php

switch($foo) {
	case 1:
		doSomething();
	break;
	case 2:
		doSomethingElse();
		break;
	default:
}

while ($i < $j) {
	if ($foo == $bar) {
		doSomething();
		break;
	} else {
		if ($oof == $rab) {
		break;
		}
	}
	break;
}

switch($foo) {
	case 1:
		doSomething();
		break;
	default:
	break;
}

do {
	doSomething();

	if ($i == $j) {
		doSomethingElse();
			break;
	}
	break;
} while ($foo != $bar);

for ($i=10; $i>0; $i--) {
	doSomething();

	break;
}

while ($uid != 0 && $loopCheck) {
	$loopCheck--;
	$row = self::getPageForRootline($uid, $clause, $workspaceOL);
	if (is_array($row)) {
		$uid = $row['pid'];
		$theRowArray[] = $row;
	} else {
		break;
	}
}

while ($uid != 0 && $loopCheck) {
	$loopCheck--;
	$row = self::getPageForRootline($uid, $clause, $workspaceOL);
	if (is_array($row)) {
		$uid = $row['pid'];
		$theRowArray[] = $row;
	} else {
	break;
	}
}

while ($uid != 0 && $loopCheck) {
	if (is_array($row)) {
		break;
	} else {
		$uid = $row['pid'];
		$theRowArray[] = $row;
	}
}
?>