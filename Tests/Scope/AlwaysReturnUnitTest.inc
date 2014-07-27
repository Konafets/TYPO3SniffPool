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

/**
 * A class used for testing the AlwaysReturnSniff.
 */
class Foo {
	/**
	 * Some function.
	 *
	 * @return void
	 */
	public function process() {
		$foo = 8;
		if ($foo < 10) {
			return;
		}

		$bar = 4;
	}

	/**
	 * Some other function.
	 *
	 * @return void
	 */
	public function processMeTwice() {
		$foo = 8;
		if ($foo < 10) {
			return
				;
		}

		$bar = 4;
	}

	/**
	 * Some other fancy (!) function.
	 *
	 * @return void
	 */
	public function thisProcessIsVeryFancy() {
		$foo = 8;
		if ($foo < 10) {
			return;
		}

		return;

		$bar = 4;
	}

	/**
	 * Some other fancy (!) function.
	 *
	 * @return void
	 */
	public function thisProcessThrowsAnError() {
		$foo = 8;
		if ($foo < 10) {
			return;
		}

		return $foo;

		$bar = 4;
	}

	/**
	 * Some function.
	 *
	 * @return integer
	 */
	public function processAnother() {
		$foo = 8;
		if ($foo < 10) {
			return;
		}

		$bar = 4;

		return $bar;
	}
}

function testWithIfAndElse() {
	if (time() % 2) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function fooBar() {
	if (time() % 2) {
		return TRUE;

	} elseif (bar) {
		return FALSE;

	} else {
		// Here should be one return statement
	}
}

function testWithTwoElseIf() {
	if (time() % 2) {

	} elseif (bar) {

	} elseif (baz) {
		return FALSE;

	} else {
		return 5;
	}
}

function testWithTwoElseIf2() {
    if (time() % 2) {
        return 'woho';

    } elseif (bar) {
        return 'hey';

    } elseif (baz) {
        return FALSE;

    } else {

    }
}

function nestedControlStructures() {
    if (5) {
        if (6) {
            return false;
        }
    }
}

function nestedControlStructures2() {
    if (5) {
        if (6) {
            return false;
        }

        return true;
    }
}

function nestedControlStructures3() {
    if (5) {
        if (6) {
            if (8) {
                if (10) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }
}

function nestedControlStructures4() {
    if (5) {
        if (6) {
            if (8) {
                if (10) {
                    return true;
                } else {
                    return false;
                }
            } elseif (7) {
                return false;
            } else {
                return false;
            }
        }

        return true;
    }
}

function nestedControlStructures5() {
    if (5) {
        if (6) {
            if (8) {
                if (10) {
                    return true;
                } else {
                    return false;
                }
            } elseif (7) {
                return false;
            } else {
                return false;
            }
        }

        return true;
    } else {
        return false;
    }
}

function nestedControlStructures6() {
    if (5) {
        if (6) {
            if (8) {
                if (10) {
                    return true;
                } else {
                    return false;
                }
            } elseif (7) {
                return false;
            } else {
                return false;
            }
        }

        return true;
    } else {
        return false;
    }

    return 7;
}

/**
 * @return void
 */
function anonymousFunc() {
    $test = function() {
        return 'bar';
    }
}

function anonymousFuncWithoutDocComment() {
    $test = function() {
        return 'bar';
    }
}

function nestedControlStructuresWithAnonymousFunc() {
    if (bar) {
        if (baz) {
            $test = function() {
                return 'bar';
            }
        }
    }
}

function nestedControlStructuresWithAnonymousFunc2() {
    if (5) {
        if (6) {
            if (8) {
                if (10) {
                    $test = function() {
                        return 'bar';
                    }
                    return true;
                } else {
                    return false;
                }
            } elseif (7) {
                return false;
            } else {
                return false;
            }
        }

        $test = function() {
            return 'bar';
        }

        return true;
    } else {
        return false;
    }

    return 7;
}
?>