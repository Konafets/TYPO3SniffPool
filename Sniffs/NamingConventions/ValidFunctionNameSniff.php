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
        if ($this->isFunctionAMagicFunction($tokens[$functionName]['content']) === TRUE
            || $this->isFunctionAPiBaseFunction($tokens[$functionName]['content']) === TRUE) {
            return NULL;
        }
        $hasUnderscores = stripos($tokens[$functionName]['content'], '_');
        $isLowerCamelCase = PHP_CodeSniffer::isCamelCaps($tokens[$functionName]['content'], FALSE, TRUE, TRUE);
        $scope = $this->getCorrectScopeOfToken($tokens, $stackPtr);
        if ($hasUnderscores !== FALSE) {
            $error = 'Underscores are not allowed in ' . $scope . ' names "' . $tokens[$functionName]['content'] . '"; ';
            $error.= 'use lowerCamelCase for ' . $scope . ' names instead';
            $phpcsFile->addError($error, $stackPtr);
        } elseif ($isLowerCamelCase === FALSE) {
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

    /**
     * Returns TRUE if called function / method is a pi_-based method
     * of TYPO3.
     * If you create a frontend plugin for TYPO3 on piBase and overwrite
     * an existing method from tslib_pibase which starts with "pi_"
     * the codesniffer will mark this as an error.
     *
     * This method contains a full list of methods who starts with "pi_"
     * and are from tslib_pibase (TYPO3 4.5.15 LTS).
     *
     * @see http://forge.typo3.org/issues/28170
     *
     * @param  string  $name
     * @return boolean
     */
    protected function isFunctionAPiBaseFunction($name) {
        $result = FALSE;

        switch ($name) {
            case 'pi_autoCache':
            case 'pi_classParam':
            case 'pi_exec_query':
            case 'pi_getCategoryTableContents':
            case 'pi_getClassName':
            case 'pi_getEditIcon':
            case 'pi_getEditPanel':
            case 'pi_getFFvalue':
            case 'pi_getFFvalueFromSheetArray':
            case 'pi_getLL':
            case 'pi_getPageLink':
            case 'pi_getPidList':
            case 'pi_getRecord':
            case 'pi_initPIflexForm':
            case 'pi_isOnlyFields':
            case 'pi_linkToPage':
            case 'pi_linkTP':
            case 'pi_linkTP_keepPIvars':
            case 'pi_linkTP_keepPIvars_url':
            case 'pi_list_browseresults':
            case 'pi_list_header':
            case 'pi_list_linkSingle':
            case 'pi_list_makelist':
            case 'pi_list_modeSelector':
            case 'pi_list_query':
            case 'pi_list_row':
            case 'pi_list_searchBox':
            case 'pi_loadLL':
            case 'pi_openAtagHrefInJSwindow':
            case 'pi_prependFieldsWithTable':
            case 'pi_RTEcssText':
            case 'pi_setClassStyle':
            case 'pi_setPiVarDefaults':
            case 'pi_wrapInBaseClass':
                $result = TRUE;
            break;
        }

        return $result;
    }
}
?>