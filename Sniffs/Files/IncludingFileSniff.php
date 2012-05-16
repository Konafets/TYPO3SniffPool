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
 * TYPO3_Sniffs_Files_IncludingFileSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Files
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright Copyright (c) 2010, Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that the include_once is used in all cases.
 *
 * @category  Files
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright Copyright (c) 2010, Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Sniffs_Files_IncludingFileSniff implements PHP_CodeSniffer_Sniff {
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register() {
        return array(T_INCLUDE_ONCE, T_REQUIRE, T_INCLUDE);
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
        $keyword = $tokens[$stackPtr]['content'];
        $tokenCode = $tokens[$stackPtr]['type'];
        switch ($tokenCode) {
            case 'T_INCLUDE_ONCE':
                    // Here we are looking if the found include_once keyword is
                    // part of an XClass declaration where this is allowed.
                if ($tokens[$stackPtr + 7]['content'] == "'XCLASS'" ) {
                    return;
                }
                $error = 'Including files with "' . $keyword . '" is not allowed; ';
                $error.= 'use "require_once" instead';
                $phpcsFile->addError($error, $stackPtr);
            break;
            case 'T_REQUIRE':
                $error = 'Including files with "' . $keyword . '" is not allowed; ';
                $error.= 'use "require_once" instead';
                $phpcsFile->addError($error, $stackPtr);
            break;
            case 'T_INCLUDE':
                $error = 'Including files with "' . $keyword . '" is not allowed; ';
                $error.= 'use "require_once" instead';
                $phpcsFile->addError($error, $stackPtr);
            break;
            default:
        }
}
}
?>