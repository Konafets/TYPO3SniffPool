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
 * TYPO3_Sniffs_Commenting_ValidCommentIndentSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright Copyright (c) 2010, Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   SVN: $ID$
 * @link      http://pear.typo3.org
 */
/**
 * Checks the indent of comments
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright Copyright (c) 2010, Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Sniffs_Commenting_ValidCommentIndentSniff implements PHP_CodeSniffer_Sniff {
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
        return array(T_COMMENT);
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
        if (substr($tokens[$stackPtr]['content'], 0, 2) === '//') {
			$commentColumn = ($tokens[$stackPtr]['column'] - 1);
			$nextNonCommentToken = $phpcsFile->findNext(array(T_COMMENT, T_DOC_COMMENT, T_WHITESPACE), $stackPtr, NULL, TRUE);
			$codeColumn = ($tokens[$nextNonCommentToken]['column'] - 1);

			if ($commentColumn < $codeColumn) {
				$tab = ($codeColumn - $commentColumn);
				$error = 'Inline comments indents ' . $tab . ' tab(s) too less; expected indent only by one tab more then the code next line.';
				$code = 'TooLessIndent';
			} else if ($commentColumn === $codeColumn) {
				$error = 'Inline comments must be indented by one tab more then the code next line.';
				$code = 'SameIndention';
			} else if ($commentColumn > $codeColumn + 1) {
				$tab = ($commentColumn - ($codeColumn + 1));
				$error = 'Inline comments indents ' . $tab . ' tab(s) too much; expected indent only by one tab more then the code next line.';
				$code = 'TooMuchIndent';
			}

			if (isset($error)) {
				$phpcsFile->addError($error, $stackPtr, $code);
			}
        }
    }
}
?>