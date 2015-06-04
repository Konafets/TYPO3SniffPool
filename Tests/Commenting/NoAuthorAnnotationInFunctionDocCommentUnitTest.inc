<?php
class NoAuthorAnnotationUnitTest {

	/**
	 * A simple function comment.
	 *
	 * long desc here
	 *
	 * @author Superman
	 * @author Yoshi
	 *
	 * @param int $stackPtr   The position in @ @unknown the stack of the token
	 *                        that opened the scope
	 * @param int $detph    How many scope levels down we are.
	 * @param int    $index    The index
	 * @return
	 * @throws
	 */
	private function throwsErrors($stackPtr, $depth=1, $index) {
		return $stackPtr;
	}

	/**
	 * A simple function comment.
	 *
	 * long desc here
	 *
	 * @param int $stackPtr   The position in @ @unknown the stack of the token
	 *                        that opened the scope
	 * @param int $detph    How many scope levels down we are.
	 * @param int    $index    The index
	 * @return
	 * @throws
	 */
	public function throwsNoErrors() {

	}

	/**
	 * @author Pacman
	 */
	public function errorNoTwo() {

	}
}

/**
 * Class Foo
 *
 * @author Stefano Kowalke
 */
class Foo {

}

/**
 * Class Bar
 *
 */
class Bar {

}

/**
 * Interface Bar
 */
interface Bar {

}

/**
 * Class Baz
 */
trait Baz {

}