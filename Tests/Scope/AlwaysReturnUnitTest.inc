<?php
function foo($bar) {
	if ($bar) {
		return 'FooBar';
	}
}

function bar($baz) {
	if ($baz) {
		return 'BarBaz';
	}

	return '';
}

class FooBarBaz {
	public function foo($bar) {
		if ($bar) {
			return 'FooBar';
		}
	}

	public function bar($baz) {
		if ($baz) {
			return 'BarBaz';
		}

		return '';
	}
}

function fooBarBaz($barParam) {
	return 'FooBar';
}

if($foo === $bar) {
	return 5;
}

return NULL;
?>