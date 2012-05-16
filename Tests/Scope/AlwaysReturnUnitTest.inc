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

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param int                  $stackPtr  The position of the current token in the
 *                                        stack passed in $tokens.
 *
 * @return void
 */
function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
	// No return value here, because of @return in doc header
}

function hasNoReturnValue() {
	// This function must have a return statement,
	// because no doc block comment with return void :(
}

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param int                  $stackPtr  The position of the current token in the
 *                                        stack passed in $tokens.
 *
 * @return void
 */
function processWithReturnStatement(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
	// Return value here WITH @return void. This sould be an error ;)
	return 'foo';
}

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param int                  $stackPtr  The position of the current token in the
 *                                        stack passed in $tokens.
 *
 * @return void
 */
function processWithReturnStatementInControlStructure(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
	// Return value here WITH @return void. This sould be an error ;)
	if($foo == $bar) {
		return 'bar';
	}
}

class t3lib_autoloader {

	/**
	 * The autoloader is static, thus we do not allow instances of this class.
	 */
	private function __construct() {
	}

	/**
	 * Installs TYPO3 autoloader, and loads the autoload registry for the core.
	 *
	 * @return boolean TRUE in case of success
	 */
	public static function registerAutoloader() {
		self::loadCoreAndExtensionRegistry();
		return spl_autoload_register('t3lib_autoloader::autoload', TRUE, TRUE);
	}
}

interface fileList_editIconHook {

	/**
	 * modifies edit icon array
	 *
	 * @param	array					array of edit icons
	 * @param	fileList	parent object
	 * @return	void
	 */
	public function manipulateEditIcons(&$cells, &$parentObject);

}
?>