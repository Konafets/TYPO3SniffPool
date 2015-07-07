<?php

$string = 'Hello'.$there.'. How are'.$you.$going."today $okay";
$string = 'Hello' . $there . '. How are' . $you . $going . "today $okay";
$string = 'Hello'.$there;
$string = 'Hello'. $there;
$string = 'Hello' .$there;
$string = 'Hello'  .  $there;
$string = 'Hello' .   $there;

// Old rule < TYPO3 7.0
$content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ' .
	'Donec varius libero non nisi. Proin eros.';

// New rule > TYPO3 7.0
$content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. '
	. 'Donec varius libero non nisi. Proin eros.';


