<?php
class foo {
 // some code
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myext/pi1/class.tx_myext_pi1.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myext/pi1/class.tx_myext_pi1.php']);
}
?>
<?php
class bar {
 // some code
}

?>
