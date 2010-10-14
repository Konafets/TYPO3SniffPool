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
 * TYPO3_Sniffs_Whitespaces_DetectWhitespaceViolationsSniff.
 *
 * Checks that equal operator surrounded by spaces.
 * false: $foo='Hello World';
 * right: $foo = 'Hello World';
 *
 * @category    PHP
 * @package     TYPO3
 * @subpackage  PHPCodeSniffer
 * @author      Stefano Kowalke <blueduck@gmx.net>
 * @version     Release: 0.1
 */
class TYPO3v4_Sniffs_Whitespaces_DetectWhitespaceViolationsSniff implements PHP_CodeSniffer_Sniff {
	/**
	 * @var string The content of the previous token
	 */
	protected $previousKeyword;
	/**
	 * @var string The trivial name of the previous token
	 */
	protected $previousToken;
	/**
	 * @var string The content of the current token
	 */
	protected $currentKeyword;
	/**
	 * @var string The content of the nect token
	 */
	protected $nextKeyword;
	/**
	 * @var string The trivial name of the next token
	 */
	protected $nextToken;
	/**
	 * @var string The trivial name of the first token on this line
	 */
	protected $firstOnLineTokenName;
	/**
	 * A list of tokenizers this sniff supports
	 *
	 * @var array
	 */
	public $supportedTokenizes = array('PHP', 'JS',);
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array( // String concatenation
		T_STRING_CONCAT, T_COMMA,
		// Compare operators
		T_LESS_THAN, T_IS_SMALLER_OR_EQUAL, T_GREATER_THAN, T_IS_GREATER_OR_EQUAL, T_IS_EQUAL, T_IS_NOT_EQUAL, T_IS_IDENTICAL, T_IS_NOT_IDENTICAL,
		// Assignment operators
		T_EQUAL, T_CONCAT_EQUAL, T_PLUS_EQUAL, T_MINUS_EQUAL, T_MUL_EQUAL, T_DIV_EQUAL, T_MOD_EQUAL, T_DOUBLE_ARROW,
		// Arithmetic operators
		T_PLUS, T_MINUS, T_MULTIPLY, T_DIVIDE, T_MODULUS,
		// Boolean/Logical operators
		T_BOOLEAN_AND, T_BOOLEAN_NOT, T_BOOLEAN_OR, T_LOGICAL_AND, T_LOGICAL_OR, T_LOGICAL_XOR, T_INLINE_THEN, T_COLON,
		// Bitwise operators
		T_BITWISE_AND, T_BITWISE_OR, T_POWER, T_SL, T_SR,
		// some others
		T_AS,);
	} //end register()
	
	/**
	 * Processes this sniff, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token in
	 * 											the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$this->previousKeyword = $tokens[$stackPtr - 1]['content'];
		$this->previousToken = $tokens[$stackPtr - 1]['type'];
		$this->currentKeyword = $tokens[$stackPtr]['content'];
		$this->nextKeyword = $tokens[$stackPtr + 1]['content'];
		$this->nextToken = $tokens[$stackPtr + 1]['type'];
		$token = $tokens[$stackPtr]['type'];
		$firstOnLineTokenID = $phpcsFile->findFirstOnLine(T_RETURN, $stackPtr);
		$this->firstOnLineTokenName = $tokens[$firstOnLineTokenID]['type'];
		/*
		
		echo '---------------------------------' . "\n";
		echo '$token: ' . $token . "\n";
		echo '$stackPtr: ' . $stackPtr . "\n";
		echo 'Line: ' . $tokens[$stackPtr]['line'] . "\n";
		echo '$previousKeyword: ' . $this->previousKeyword . "\n";
		echo '$previousToken: ' . $this->previousToken . "\n";
		echo '$currentKeyword: ' . $this->currentKeyword . "\n";
		echo '$nextKeyword: ' . $this->nextKeyword . "\n";
		echo '$nextToken: ' . $this->nextToken . "\n";
		echo '$firstOnLineTokenName: ' . $this->firstOnLineTokenName . "\n";
		#var_dump($tokens);
		*/
		switch ($tokens[$stackPtr]['type']) {
				// String concatenation
				
			case 'T_STRING_CONCAT':
				$this->whitespaces($phpcsFile, $stackPtr, '.', 'Concatenation', 'ConcatString');
			break;
			case 'T_COMMA':
				$this->whitespaces($phpcsFile, $stackPtr, ',', 'Comma', 'Comma', 'r');
			break;
				// Compare operators
				
			case 'T_LESS_THAN':
				$this->whitespaces($phpcsFile, $stackPtr, '<', 'Less-Than', 'LessThan');
			break;
			case 'T_IS_SMALLER_OR_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '<=', 'Smaller-Or-Equal', 'SmallerOrEqual');
			break;
			case 'T_GREATER_THAN':
				$this->whitespaces($phpcsFile, $stackPtr, '>', 'Greater-Than', 'GreaterThan');
			break;
			case 'T_IS_GREATER_OR_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '>=', 'Greater-Or-Equal', 'GreaterOrEqual');
			break;
			case 'T_IS_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '==', 'Equal', 'Equal');
			break;
			case 'T_IS_NOT_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '!=', 'Not-Equal', 'Not-Equal');
			break;
			case 'T_IS_IDENTICAL':
				$this->whitespaces($phpcsFile, $stackPtr, '===', 'Identical', 'Identical');
			break;
			case 'T_IS_NOT_IDENTICAL':
				$this->whitespaces($phpcsFile, $stackPtr, '!==', 'Not-Identical', 'Not-Identical');
			break;
				// Assignments operators
				
			case 'T_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '=', 'Assignment Equal', 'AssignEqual');
			break;
			case 'T_CONCAT_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '.=', 'Concatentation Equal', 'ConcatEqual');
			break;
			case 'T_PLUS_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '+=', 'Plus-Equal', 'PlusEqual');
			break;
			case 'T_MINUS_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '-=', 'Minus-Equal', 'MinusEqual');
			break;
			case 'T_MUL_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '*=', 'Multipy-Equal', 'MultiplyEqual');
			break;
			case 'T_DIV_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '/=', 'Divide-Equal', 'DivideEqual');
			break;
			case 'T_MOD_EQUAL':
				$this->whitespaces($phpcsFile, $stackPtr, '%=', 'Modulo-Equal', 'ModuloEqual');
			break;
			case 'T_DOUBLE_ARROW':
				$this->whitespaces($phpcsFile, $stackPtr, '=>', 'Double arrow', 'DoubleArrow');
			break;
				// Arithmetic operators
				
			case 'T_PLUS':
				$this->whitespaces($phpcsFile, $stackPtr, '+', 'Plus', 'Plus');
			break;
			case 'T_MINUS':
				$this->whitespaces($phpcsFile, $stackPtr, '-', 'Minus', 'Minus');
			break;
			case 'T_MULTIPLY':
				$this->whitespaces($phpcsFile, $stackPtr, '*', 'Multiplication', 'Multiply');
			break;
			case 'T_DIVIDE':
				$this->whitespaces($phpcsFile, $stackPtr, '/', 'Division', 'Divide');
			break;
			case 'T_MODULUS':
				$this->whitespaces($phpcsFile, $stackPtr, '%', 'Modulo', 'Modulo');
			break;
				// Boolean/Logical operators
				
			case 'T_BOOLEAN_AND':
				$this->whitespaces($phpcsFile, $stackPtr, '&&', 'Boolean-And', 'BoolAnd');
			break;
			case 'T_BOOLEAN_NOT':
				#$this->whitespaces($phpcsFile, $stackPtr, '!', 'Boolean-Not', 'BoolNot');
				
			break;
			case 'T_BOOLEAN_OR':
				$this->whitespaces($phpcsFile, $stackPtr, '||', 'Boolean-Or', 'BoolOr');
			break;
			case 'T_LOGICAL_AND':
				$this->whitespaces($phpcsFile, $stackPtr, 'AND', 'Logical-And', 'LogAnd');
			break;
			case 'T_LOGICAL_OR':
				$this->whitespaces($phpcsFile, $stackPtr, 'OR', 'Logical-Or', 'LogOr');
			break;
			case 'T_LOGICAL_XOR':
				$this->whitespaces($phpcsFile, $stackPtr, 'XOR', 'Logical-Xor', 'LogXor');
			break;
			case 'T_INLINE_THEN':
				$this->whitespaces($phpcsFile, $stackPtr, '?', 'Ternary question mark', 'TernaryQuestMark');
			break;
			case 'T_COLON':
				$this->whitespaces($phpcsFile, $stackPtr, ':', 'Ternary colon', 'TernaryColon');
			break;
				// Bitwise operators
				
			case 'T_BITWISE_AND':
				#$this->whitespaces($phpcsFile, $stackPtr, '&', 'Bitwise-And', 'BitAnd', 'l');
				
			break;
			case 'T_BITWISE_OR':
				$this->whitespaces($phpcsFile, $stackPtr, '|', 'Bitwise-Or', 'BitOr');
			break;
			case 'T_POWER':
				$this->whitespaces($phpcsFile, $stackPtr, '^', 'Bitwise-Xor', 'BitXor');
			break;
			case 'T_SL':
				$this->whitespaces($phpcsFile, $stackPtr, '<<', 'Shift-Left', 'ShiftLeft');
			break;
			case 'T_SR':
				$this->whitespaces($phpcsFile, $stackPtr, '>>', 'Shift-Right', 'ShiftRight');
			break;
			case 'T_AS':
				$this->whitespaces($phpcsFile, $stackPtr, 'as', 'As', 'As');
			break;
			case 'XXX':
				#$this->whitespaces($phpcsFile, $stackPtr, '<<=', 'Bitwise-Xor', 'BitXor');
				
			break;
			default:
		}
}
/**
 *	Checks for whitespace violations
 *
 *	@param PHP_CodeSniffer_File $phpcsFile
 *	@param int $stackPtr
 *	@param string $match 		The token we are looking for
 *	@param string $operatorName The trivial name of this token: && -> Boolean-And
 *	@param string $operatorID 	The unique ID of the token name in CamelCase: && -> BooleanAnd
 *	@param string $where 		Specify where the function search. Left from token (l),
 *								right from token (r), or both.
 *								Default: lr
 *	@example	$add = 1; 	  <- correct
 *				$add= 1; 	  <- incorrect
 *				$add =1;	  <- incorrect
 *				$add=1; 	  <- incorrect
 *				$add  =  1;	  <- incorrect
 *				$sub = 10 - 1 <- correct
 *				return -1     <- correct
 *
 */
public function whitespaces(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $match, $operatorName, $operatorID, $where = 'lr') {
	if ($this->currentKeyword === $match) {
		if ($where === 'lr') {
			if ($this->firstOnLineTokenName === 'T_RETURN' && $this->currentKeyword === '-') {
				if ($this->previousToken !== 'T_WHITESPACE') {
					$error = $operatorName . ' operator must be surrounded by space at the left side; expected " ' . $match . '"; but found "' . trim($this->previousKeyword) . $this->currentKeyword . '"';
					$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorSpaceLeft');
				} elseif ($this->previousToken === 'T_WHITESPACE' && $this->nextToken === 'T_WHITESPACE') {
					$error = $operatorName . ' operator must be surrounded by space at the left side, but not by space at the right; expected " ' . $match . '1", but found "' . $this->previousKeyword . $this->currentKeyword . $this->nextKeyword . '"';
					$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorSpaceLeftNoSpaceRight');
				}
			} else {
				if (($this->previousToken !== 'T_WHITESPACE' && $this->nextToken !== 'T_WHITESPACE')) {
					$error = $operatorName . ' operator must be surrounded by spaces at the left and right side; expected " ' . $match . ' "; but found "' . trim($this->previousKeyword) . $this->currentKeyword . trim($this->nextKeyword) . '"';
					$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorNoSpaceLeftNoSpaceRight');
				} elseif (($this->previousToken !== 'T_WHITESPACE') && ($this->nextToken === 'T_WHITESPACE')) {
					$error = $operatorName . ' operator must be surrounded by spaces at the left side; expected " ' . $match . '"; but found "' . trim($this->previousKeyword) . $this->currentKeyword . trim($this->nextKeyword) . '"';
					$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorNoSpaceLeft');
				} elseif (($this->previousToken === 'T_WHITESPACE') && ($this->nextToken !== 'T_WHITESPACE')) {
					$error = $operatorName . ' operator must be surrounded by spaces at the right side; expected "' . $match . ' "; but found "' . trim($this->previousKeyword) . $this->currentKeyword . trim($this->nextKeyword) . '"';
					$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorNoSpaceRight');
				}
			}
		} elseif ($where === 'l') {
			if ($this->previousToken !== 'T_WHITESPACE') {
				$error = $operatorName . ' operator must be surrounded by space at the left side; expected " ' . $match . '"; but found "' . trim($this->previousKeyword) . $this->currentKeyword . '"';
				$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorSpaceLeft');
			} elseif ($this->previousToken === 'T_WHITESPACE' && $this->nextToken === 'T_WHITESPACE') {
				$error = $operatorName . ' operator must be surrounded by space at the left side, but not by space at the right; expected " ' . $match . '$foo", but found "' . $this->previousKeyword . $this->currentKeyword . $this->nextKeyword . '"';
				$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorSpaceLeftNoSpaceRight');
			}
		} elseif ($where === 'r') {
			if (($this->previousToken === 'T_WHITESPACE') && ($this->nextToken !== 'T_WHITESPACE')) {
				$error = $operatorName . ' operator must be surrounded by space at the right side, but not at the left; expected "' . $match . ' "; but found "' . $this->previousKeyword . $this->currentKeyword . $this->nextKeyword . '"';
				$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorSpaceRightNoSpaceLeft');
			} elseif (($this->previousToken !== 'T_WHITESPACE') && ($this->nextToken !== 'T_WHITESPACE')) {
				$error = $operatorName . ' operator must be surrounded by space at the right side; expected "' . $match . ' "; but found "' . $this->previousKeyword . $this->currentKeyword . $this->nextKeyword . '"';
				$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorSpaceRight');
			} elseif (($this->previousToken === 'T_WHITESPACE') && ($this->nextToken === 'T_WHITESPACE')) {
				$error = $operatorName . ' operator must not have a space at the left side; expected "' . $match . ' "; but found "' . $this->previousKeyword . $this->currentKeyword . $this->nextKeyword . '"';
				$phpcsFile->addError($error, $stackPtr, $operatorID . 'OperatorNoSpaceLeft');
			}
		}
	}
}
}
?>