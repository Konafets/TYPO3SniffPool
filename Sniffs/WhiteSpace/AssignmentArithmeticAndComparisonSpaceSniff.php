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
 * TYPO3_Sniffs_WhiteSpace_AssignmentArithmeticAndComparisonSpaceSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright Copyright (c) 2010, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that one whitespace is before and after an assignment, arithmethic and comparison token.
 *
 * Correct:   $foo = $bar;
 * Incorrect: $foo=$bar;
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright Copyright (c) 2010, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Sniffs_WhiteSpace_AssignmentArithmeticAndComparisonSpaceSniff implements PHP_CodeSniffer_Sniff {
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
        $registeredTokens = array_merge(PHP_CodeSniffer_Tokens::$assignmentTokens, PHP_CodeSniffer_Tokens::$arithmeticTokens);
        $registeredTokens = array_merge($registeredTokens, PHP_CodeSniffer_Tokens::$comparisonTokens);
        return $registeredTokens;
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
        $found = $this->getFoundString($tokens, $stackPtr);
        $kindOfToken = $this->getKindOfToken($tokens[$stackPtr]);
        // The following code sniplet was copied and modified from Squiz_Sniffs_Formatting_OperatorBracketSniff
        // Thanks for this guys!
        // There is one instance where brackets aren't needed, which involves
        // the minus sign being used to assign a negative number to a variable.
        if ($tokens[$stackPtr]['code'] === T_MINUS) {
            // Check to see if we are trying to return -n.
            $prev = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
            if ($tokens[$prev]['code'] === T_RETURN) {
                return;
            }
            $number = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), NULL, TRUE);
            if ($tokens[$number]['code'] === T_LNUMBER || $tokens[$number]['code'] === T_DNUMBER) {
                $previous = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), NULL, TRUE);
                if ($previous !== FALSE) {
                    $isAssignment = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$assignmentTokens);
                    $isEquality = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$equalityTokens);
                    $isComparison = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$comparisonTokens);
                    $isArithmetic = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$arithmeticTokens);
                    $isFunctionOrArray = in_array($tokens[$previous]['code'], array(T_OPEN_PARENTHESIS, T_OPEN_SQUARE_BRACKET));
                    $isFunctionArgument = ($tokens[$previous]['code'] === T_COMMA);
                    if ($isAssignment === TRUE || $isEquality === TRUE || $isComparison === TRUE || $isArithmetic === TRUE || $isFunctionOrArray === TRUE || $isFunctionArgument === TRUE) {
                        // This is a negative assignment, comparion, calculcation, function argument or array key usage.
                        // We need to check that the minus and the number are adjacent.
                        if (($number - $stackPtr) !== 1) {
                            $error = 'No space allowed between minus sign and number';
                            $phpcsFile->addError($error, $stackPtr, 'SpacingAfterMinus');
                        }
                        return;
                    }
                }
            }
        }
        if ($this->existsWhitespace('before', $tokens, $stackPtr) === FALSE && $this->existsWhitespace('after', $tokens, $stackPtr) === FALSE) {
            $expected = $this->getExpectedString('before-after', $tokens, $stackPtr);
            $phpcsFile->addError('Whitespace must be added before and after the ' . $kindOfToken . ' operator. Found "' . $found . '". Expected "' . $expected . '"', $stackPtr);
        } elseif ($this->existsWhitespace('before', $tokens, $stackPtr) === FALSE) {
            $expected = $this->getExpectedString('before', $tokens, $stackPtr);
            $phpcsFile->addError('Whitespace must be added before the ' . $kindOfToken . ' operator. Found "' . $found . '". Expected "' . $expected . '"', $stackPtr);
        } elseif ($this->existsWhitespace('after', $tokens, $stackPtr) === FALSE) {
            $expected = $this->getExpectedString('after', $tokens, $stackPtr);
            $phpcsFile->addError('Whitespace must be added after the ' . $kindOfToken . ' operator. Found "' . $found . '". Expected "' . $expected . '"', $stackPtr);
        }
    }
    protected function getKindOfToken(array $token) {
        $result = '';
        if (in_array($token['code'], PHP_CodeSniffer_Tokens::$assignmentTokens)) {
            $result = 'assignment';
        } elseif (in_array($token['code'], PHP_CodeSniffer_Tokens::$arithmeticTokens)) {
            $result = 'arithmetic';
        } elseif (in_array($token['code'], PHP_CodeSniffer_Tokens::$comparisonTokens)) {
            $result = 'comparison';
        }
        return $result;
    }
    protected function getFoundString(array $tokens, $stackPtr) {
        $found = $tokens[($stackPtr - 1) ]['content'] . $tokens[$stackPtr]['content'] . $tokens[($stackPtr + 1) ]['content'];
        return $found;
    }
    protected function getExpectedString($mode, array $tokens, $stackPtr) {
        $expected = $tokens[($stackPtr - 1) ]['content'];
        switch (strtolower($mode)) {
            case 'before':
                $expected.= ' ' . $tokens[$stackPtr]['content'];
            break;
            case 'after':
                $expected.= $tokens[$stackPtr]['content'] . ' ';
            break;
            case 'before-after':
                $expected.= ' ' . $tokens[$stackPtr]['content'] . ' ';
            break;
        }
        $expected.= $tokens[($stackPtr + 1) ]['content'];
        return $expected;
    }
    protected function existsWhitespace($mode, array $tokens, $stackPtr) {
        $result = FALSE;
        $stackPtr = $this->manageStackPtrCounter($mode, $stackPtr);
        if ($tokens[$stackPtr]['code'] == T_WHITESPACE) {
            $result = TRUE;
        }
        return $result;
    }
    protected function manageStackPtrCounter($mode, $stackPtr) {
        switch (strtolower($mode)) {
            case 'before':
                $stackPtr--;
            break;
            case 'after':
                $stackPtr++;
            break;
            default:
        }
        return $stackPtr;
}
}
?>