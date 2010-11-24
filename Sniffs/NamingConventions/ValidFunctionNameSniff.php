<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2010 Laura Thewalt <laura.thewalt@wmdb.de>
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
 * TYPO3_Sniffs_NamingConventions_ValidFunctionNameSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  NamingConventions
 * @package   TYPO3_PHPCS_Pool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @author    Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright Copyright (c) 2010, Laura Thewalt, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   SVN: $ID$
 * @link      http://pear.typo3.org
 */
/**
 * Checks that the functions named by lowerCamelCase
 *
 * No Underscores are allowed
 * Correct:   function testFunctionName ()
 * Incorrect: function Test_Function_name ()
 *            function TestFunctionname ()
 *
 * @category  NamingConventions
 * @package   TYPO3_PHPCS_Pool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @author    Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright Copyright (c) 2010, Laura Thewalt, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Sniffs_NamingConventions_ValidFunctionNameSniff implements PHP_CodeSniffer_Sniff {
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
        return array(T_FUNCTION);
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
        $tokens = $phpcsFile->getTokens();
        $functionName = $phpcsFile->findNext(array(T_STRING), $stackPtr);
        if ($this->isFunctionAMagicFunction($tokens[$functionName]['content']) === TRUE) {
            return NULL;
        }
        $hasUnderscores = stripos($tokens[$functionName]['content'], '_');
        $isLowerCamelCase = preg_match('/(\b[a-z]{1,})\b|(\b[a-z]{1,})([A-Z]{1}[a-z]{1,}){1,}\b/', $tokens[$functionName]['content']);
        $scope = $this->getCorrectScopeOfToken($tokens, $stackPtr);
        if ($hasUnderscores !== FALSE) {
            $error = 'Underscores are not allowed in ' . $scope . ' names "' . $tokens[$functionName]['content'] . '"; ';
            $error.= 'use lowerCamelCase for ' . $scope . ' names instead';
            $phpcsFile->addError($error, $stackPtr);
        } elseif ($isLowerCamelCase === 0) {
            $error = ucfirst($scope) . ' name "' . $tokens[$functionName]['content'] . '" must use lowerCamelCase';
            $phpcsFile->addError($error, $stackPtr);
        }
    }
    public function getCorrectScopeOfToken(array $tokens, $stackPtr) {
        $scope = 'function';
        if (!is_array($tokens[$stackPtr]['conditions'])) {
            return $scope;
        }
        foreach ($tokens[$stackPtr]['conditions'] as $tokenNumber => $tokenType) {
            if ($tokenType == T_CLASS) {
                $scope = 'method';
                break;
            }
        }
        return $scope;
    }
    /**
     * Returns TRUE if the called function / method is a magic method of php
     *
     * @see http://php.net/manual/en/language.oop5.magic.php
     *
     * @param  string  $name
     * @return boolean
     */
    public function isFunctionAMagicFunction($name) {
        $result = FALSE;
        switch ($name) {
            case '__construct':
            case '__destruct':
            case '__call':
            case '__callStatic':
            case '__get':
            case '__set':
            case '__isset':
            case '__unset':
            case '__sleep':
            case '__wakeup':
            case '__toString':
            case '__invoke':
            case '__set_state':
            case '__clone':
            case '__autoload':
                $result = TRUE;
            break;
        }
        return $result;
    }
}
?>