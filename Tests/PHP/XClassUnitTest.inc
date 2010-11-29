<?php
class foo {
 // some code
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/phpcs/XClassUnitTest.inc']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/phpcs/XClassUnitTest.inc']);
}
?>
<?php
class baz {
 // some code
}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS'][])) {
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['']);
}
?>
<?php
class bar {
 // some code
}

?>