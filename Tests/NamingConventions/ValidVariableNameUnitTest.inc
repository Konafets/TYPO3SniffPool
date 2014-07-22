<?php
class foo {
 	public $FooBar = NULL;
	public $Foo_Bar = NULL;
	public $FOOBar = NULL;
	public $barBaz = NULL;

	public function bar() {
        global $GLOBALS;

        $GLOBALS = 'bar';
		$Another_Bad_Name = array();
		$BAD_NAME = TRUE;
		$fooBarBaz = FALSE;
	}
}

// Bad names
$BAD_Name = 4;
$BaD_NAmE = 6;
$someNiceHTMLCode;
$TYPO3ReservedFields;
$sectionID;

// Good names
$goodName = 5;
$GLOBALS = 'bar';
$_EXTKEY = 'tt_sok';
$EM_CONF = array();
$someNiceHtmlCode;
$uid1 = 42;
$utf8String = 'Hello world!';

$GLOBALS = 'bar';
$TYPO3_CONF_VARS = 'bar';
$TYPO3_LOADED_EXT = 'bar';
$TYPO3_DB = 'bar';
$EXEC_TIME = 'bar';
$SIM_EXEC_TIME = 'bar';
$TYPO_VERSION = 'bar';
$CLIENT = 'bar';
$PARSETIME_START = 'bar';
$PAGES_TYPES = 'bar';
$ICON_TYPES = 'bar';
$LANG_GENERAL_LABELS = 'bar';
$TCA = 'bar';
$TBE_MODULES = 'bar';
$TBE_STYLES = 'bar';
$T3_SERVICES = 'bar';
$T3_VAR = 'bar';
$FILEICONS = 'bar';
$WEBMOUNTS = 'bar';
$FILEMOUNTS = 'bar';
$BE_USER = 'bar';
$TBE_MODULES_EXT = 'bar';
$TCA_DESCR = 'bar';
$_EXTKEY = 'bar';
$EM_CONF = 'bar';
$LANG = 'bar';
$BACK_PATH = 'bar';
$_REQUEST = 'bar';
$_SERVER = 'bar';
$_REQUEST = 'bar';
$_COOKIE = 'bar';
$_FILES  = 'bar';
$_GET = 'bar';
$_GET['foo'] = 'bar';
$_POST = 'bar';
$_POST['foo'] = 'bar';

$_ = 'foo';

function foo() {
    foreach ($variable as $key => $_) {

    }
}

foreach ($variable as $key => $value) {

}

foreach ($variable as $key => $_) {

}
?>