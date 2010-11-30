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

// Good names
$goodName = 5;
$GLOBALS = 'bar';
$_EXTKEY = 'tt_sok';
$EM_CONF = array();
$someNiceHtmlCode;
?>