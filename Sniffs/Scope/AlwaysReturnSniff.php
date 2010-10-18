<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2010 Andy Grunwald <andreas.grunwald@wmdb.de>
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
 * TYPO3_Sniffs_Scope_AlwaysReturnSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category    Scope
 * @package     TYPO3_PHPCS_Pool
 * @author      Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright	Copyright (c) 2010, Andy Grunwald
 * @license		http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version     SVN: $ID$
 * @link		http://pear.typo3.org
 */

/**
 * Checks that a function / method always have a return value if it return something.
 *
 * @category    Scope
 * @package     TYPO3_PHPCS_Pool
 * @author      Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright	Copyright (c) 2010, Andy Grunwald
 * @license		http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version     Release: @package_version@
 * @link		http://pear.typo3.org
 */
class TYPO3_Sniffs_Scope_AlwaysReturnSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * A list of tokenizers this sniff supports
	 *
	 * @var array
	 */
	public $supportedTokenizes = array('PHP');

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(T_RETURN);
	}

	/**
	 * Processes this sniff, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token in
	 *                                        the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$functionTokenKey = 0;
		$tokens = $phpcsFile->getTokens();
		
		$continue = $this->isReturnSurroundedByControllStructures($tokens, $stackPtr, $functionTokenKey);
		$functionToken = $tokens[$functionTokenKey];
		
		if ($continue === FALSE || $functionToken['code'] !== T_FUNCTION){
			return;
		}
		
		$start = $functionToken['scope_opener'];
		$result = FALSE;
		
		do {
			$next = $phpcsFile->findNext($this->register(), $start, $functionToken['scope_closer']);
			if($next !== FALSE && $next != $stackPtr && $this->isReturnSurroundedByControllStructures($tokens, $next) === FALSE){
				$result = TRUE;
				$next = FALSE;
				
			} else {
				$start = ($next + 1);
			}
		}while($next !== FALSE);
		
		if ($result === FALSE) {
			$error = 'This function must always have a return value';
			$phpcsFile->addError($error, $functionTokenKey);
		}
	}
	
	protected function isReturnSurroundedByControllStructures(array $tokens, $stackPtr, &$functionToken = 0){
		$result = FALSE;
		
		foreach ($tokens[$stackPtr]['conditions'] as $key => $val){
			if ($tokens[$key]['code'] == T_FUNCTION) {
				$functionToken = $key;
			}
			
			if ($tokens[$key]['code'] != T_CLASS && $tokens[$key]['code'] != T_FUNCTION) {
				$result = TRUE;
			}
		}
		
		return $result;
	}
}
?>