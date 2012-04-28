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
 * TYPO3_Sniffs_Debug_DebugCodeSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Debug
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright Copyright (c) 2010, Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Warns about the use of debug code like
 * print_r(), var_dump(), xdebug, debug and t3lib_div::debug
 *
 * @category  Debug
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright Copyright (c) 2010, Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Sniffs_Debug_DebugCodeSniff implements PHP_CodeSniffer_Sniff {
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP');
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register() {
        return array(T_STRING, T_COMMENT,);
    }
    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();
        $tokenType = $tokens[$stackPtr]['type'];
        $currentToken = $tokens[$stackPtr]['content'];
        switch ($tokenType) {
            case 'T_STRING':
                if ($currentToken === 'debug') {
                    $previousToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), NULL, TRUE);
                    if ($tokens[$previousToken]['content'] === '::') {
                        $errorData = array($tokens[$previousToken - 1]['content']);
                        $error = 'Call to debug function %s::debug() must be removed';
                        $phpcsFile->addError($error, $stackPtr, 'StaticDebugCall', $errorData);
                    } elseif (($tokens[$previousToken]['content'] === '->') || ($tokens[$previousToken]['content'] === 'class') || ($tokens[$previousToken]['content'] === 'function')) {
                        // We don't care about code like:
                        // if ($this->debug) {...}
                        // class debug {...}
                        // function debug () {...}
                        return;
                    } else {
                        $errorData = array($currentToken);
                        $error = 'Call to debug function %s() must be removed';
                        $phpcsFile->addError($error, $stackPtr, 'DebugFunctionCall', $errorData);
                    }
                } elseif ($currentToken === 'print_r' || $currentToken === 'var_dump' || $currentToken === 'xdebug') {
                    $errorData = array($currentToken);
                    $error = 'Call to debug function %s() must be removed';
                    $phpcsFile->addError($error, $stackPtr, 'NativDebugFunction', $errorData);
                }
            break;
            case 'T_COMMENT':
                $comment = $tokens[$stackPtr]['content'];
                $ifDebugInComment = preg_match_all('/\b((t3lib_div::)?([x]?debug)|(print_r)|(var_dump))([\s]+)?\(/', $comment, $matchesArray);
                if ($ifDebugInComment === 1) {
                    $error = 'Its not enough to comment out debug functions calls; ';
                    $error.= 'they must be removed from code.';
                    $phpcsFile->addError($error, $stackPtr, 'CommentOutDebugCall');
                }
            break;
            default:
        }
    }
} //end class
?>