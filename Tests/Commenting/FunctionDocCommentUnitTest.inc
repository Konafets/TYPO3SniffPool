<?php
class PHPCodeSnifferFileUnitTestFileForFunctionDocComment {

	/**
	 * A simple function comment.
	 *
	 * long desc here
	 *
	 * @param string $stackPtr The position in @ @unknown the stack of the token that opened the scope.
	 * @param int $depth How many scope levels down we are.
	 * @param int $index The index
	 * @return integer
	 * @throws Exception
	 */
	private function foo($stackPtr, $depth=1, $index) {
		return $stackPtr;
	}

	/**
	 * A simple function comment.
	 *
	 * long desc here
	 *
	 * @param integer $stackPt  The position in @ @unknown the stack of the token that opened the scope
	 * @param boolean $detph    How many scope levels down we are.
	 * @param array $index    The index.
	 * @return
	 * @throws
	 */
	private function foo($stackPtr, $depth=1, array $index) {
		return $stackPtr;
	}

	//
	// Sample function comment
	//
	//
	//
	public function invalidCommentStyle() {

	}


	/**
	 *
	 *
	 * A simple function comment
	 *
	 *
	 * Long description with extra blank line before and after
	 *
	 *
	 * @return     void
	 */
	public function extraDescriptionNewlines() {

	}


	/**
	 * A simple function comment
	 * @return void
	 */
	public function missingNewlinesBeforeTags() {

	}


	/**
	 * Access tag should not be treated as a long description
	 *
	 * @access public
	 * @return void
	 */
	public function accessTag() {

	}

	/**
	 * Constructor
	 */
	function __construct() {
		return;
	}

	/**
	 * Destructor
	 */
	function __destruct() {
		return;
	}


	function missingComment() {
		return;
	}


	/**
	 * no return tag
	 *
	 */
	public function noReturn($one) {

	}


	/**
	 * Param not immediate
	 *
	 * @return
	 * @param int $threeSpaces Comment.
	 * @param int $superfluous
	 * @param int Comment
	 * @param int
	 * @param missing
	 * @param
	 */
	public function missingDescriptionOfParameters($threeSpaces) {

	}


	/**
	 * Params intend style
	 *
	 * @param   int			$one	parameter comment must start with capital letter.
	 * @param	int         $two	Comment.
	 * @param	string		$three	Comment.
	 * @param	array		$four   Comment.
	 * @param	array		$five	Comment.
	 * @param	array		$five	Comment.
	 * @return	void
	 */
	public function paramsIntendStyle($one, $two, $three, $four, $five) {

	}
}


/**
 * A simple function comment
 *
 * @param string $str The string passed in by reference.
 *
 * @return void
 */
function functionOutsideClass(&$str) {
	return;
}


function missingCommentOutsideClass() {
	return;
}


?>
<?php
function tagBeforeComment() {
	return;
}


/**
 * no return tag
 *
 *
 *
 */
function noReturnOutsideClass() {

}


/**
 * Missing param comment
 *
 * @param int $one comment.
 *
 * @return void
 * @fine  Unknown tag
 */
function missingTwoParamComment($one, $two, $three) {

}


/**
 *
 */
function emptyFunctionDocComment() {

}


/**
 * Test function.
 *
 * @param string $arg1 An argument.
 *
 * @access public
 * @return bool
 */
function myFunction($arg1) {

}


/**
 * Test function.
 *
 * @param string $arg1 An argument.
 *
 * @access public
 * @return bool
 */

echo $blah;

function myFunction($arg1) {

}

/**
 * Test function.
 *
 * @access public
 * @return bool
 * @throws MyException when I feel like it
 * @throws MyException When I feel like it.
 */
function myFunction() {

}

class MyClass {
	/**
	 * An abstract function.
	 *
	 * @return array(string)
	 */
	abstract final protected function myFunction();
}

class YourClass {

	/**
	 * Params are commented, but not passed to the function
	 * Params are indented with tabs, which is not allowed, but should
	 * be ignored for those commeted params who superfluous ($superfluous, Comment, ..)
	 *
	 * @param	int	$threeTabs	Comment.
	 * @param	int	$superfluous
	 * @param	int	Comment
	 * @param	int
	 * @param	missing
	 * @param   bla
	 * @param
	 *
	 * @return int
	 */
	public function missingDescriptionOfParameters() {
        return;
	}
}

/**
 * Test function.
 *
 * @return array
 * @return int
 */
public function myFunction(Test ) {

}

/**
 * Test function.
 *
 * @return void
 */
public function myFunction() {
    return 'test';
}

/**
 * Params are commented, but not passed to the function
 * Params are indented with tabs, which is not allowed, but should
 * be ignored for those commeted params who superfluous ($superfluous, Comment, ..)
 *
 * @param array $threeTabs Comment.
 * @param int $superfluous Comment.
 *
 * @return void
 */
public function missingDescriptionOfParameters(\Test $threeTabs, array $superFluous) {
}