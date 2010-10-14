<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2010 Stefano Kowalke <blueduck@gmx.net>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * whitespaceViolations.
 *
 * Checks if an given token are surrounded by spaces.
 * false: $foo='Hello World';
 * right: $foo = 'Hello World';
 *
 * @category    PHP
 * @package     TYPO3
 * @subpackage  PHPCodeSniffer
 * @author      Stefano Kowalke <blueduck@gmx.net>
 * @version     Release: 0.1
 */
class whitespaceViolations {
	/**
	 * @var	string	The previous keyword in stack
	 */
	private $previousKeyword;
	/**
	 * @var string The current keyword in stack
	 */
	private $currentKeyword;
	/**
	 * @var string The next keyword in stack
	 */
	private $nextKeyword;
	/**
	 * Sets the previous keyword
	 *
	 * @param string $previousKeyword
	 * @return void
	 */
	function setPreviousKeyword($previousKeyword) {
		$this->previousKeyword = $previousKeyword;
	}
	/**
	 * Gets the previous keyword
	 *
	 * @return string $previousKeyword
	 */
	function getPreviousKeyword() {
		return $this->previousKeyword;
	}
	/**
	 * Sets the current keyword
	 *
	 * @param string $currentKeyword
	 * @return void
	 */
	function setCurrentKeyword($currentKeyword) {
		$this->currentKeyword = $currentKeyword;
	}
	/**
	 * Gets the current keyword
	 *
	 * @return string $currentKeyword
	 */
	function getCurrentKeyword() {
		return $this->currentKeyword;
	}
	/**
	 * Sets the next keyword
	 *
	 * @param string $nextKeyword
	 * @return void
	 */
	function setNextKeyword($nextKeyword) {
		$this->currentKeyword = $nextKeyword;
	}
	/**
	 * Gets the next keyword
	 *
	 * @return string $nextKeyword
	 */
	function getNextKeyword() {
		$this->nextKeyword;
	}
	public function check(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $match, $operatorName, $operatorID) {
		if ($this->currentKeyword === $match && $this->previousKeyword !== ' ' && $this->nextKeyword !== ' ') {
			$error = $operatorName . ' operator must be surrounded by spaces, expected " ' . $match . ' ", but found "' . trim($this->previousKeyword) . $this->currentKeyword . trim($this->nextKeyword) . '"';
			$phpcsFile->addError($error, $stackPtr, 'No' . $operatorID . 'OperatorSpaces');
		} elseif ($this->currentKeyword === $match && $this->previousKeyword !== ' ') {
			$error = $operatorName . ' operator must be surrounded by spaces, expected " ' . $match . '", but found "' . trim($this->previousKeyword) . $this->currentKeyword . trim($this->nextKeyword) . '"';
			$phpcsFile->addError($error, $stackPtr, 'No' . $operatorID . 'OperatorSpaceLeft');
		} elseif ($this->currentKeyword === $match && $this->nextKeyword !== ' ') {
			$error = $operatorName . ' operator must be surrounded by spaces, expected "' . $match . ' ", but found "' . trim($this->previousKeyword) . $this->currentKeyword . trim($this->nextKeyword) . '"';
			$phpcsFile->addError($error, $stackPtr, 'No' . $operatorID . 'OperatorSpaceRight');
		}
	}
}
?>