<?php
debug($foo);
t3lib_div::debug();
print_r();
var_dump();
xdebug();
// debug($foo);
// t3lib_div::debug();
// print_r();
// var_dump();
// xdebug();
/* debug($foo); */
/* t3lib_div::debug(); */
/* print_r(); */
/* var_dump(); */
/* xdebug(); */
# debug($foo);
# t3lib_div::debug();
# print_r();
# var_dump();
# xdebug();
if ($this->debug) {
	$pt = t3lib_div::milliseconds();
}

class debug {
	public function debug($var = '', $header = '', $group = 'Debug') {
		$foo = 'bar';
	}
}

t3lib_utility_Debug::debug();

final class t3lib_utility_Debug {
	public static function debug($var = '', $header = '', $group = 'Debug') {

	}
}
?>