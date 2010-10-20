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
 * TYPO3_Sniffs_PHP_ClosingPHPTagSniff.
 *
 * Checks if the file does have a proper copyright notice.
 *
 * @category    PHP
 * @package     TYPO3
 * @subpackage  PHPCodeSniffer
 * @author      Stefano Kowalke <blueduck@gmx.net>
 * @version     Release: 0.1
 */
class TYPO3_Sniffs_PHP_TYPO3CopyrightNoticeSniff implements PHP_CodeSniffer_Sniff {
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
        // Listen for the "<?php"-tag because it is unique
        return array(T_OPEN_TAG);
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
        $currentPtr = $stackPtr;
        // Don«t try to "repair" this. This is good as it is and looks
        // quite well on command line output
        $copyrightNotice = '/***************************************************************  * Copyright notice                                             *                                                              * (c) YYYY Your name here <your@email.here>                    * All rights reserved                                          *                                                              * This script is part of the TYPO3 project. The TYPO3 project  * is free software; you can redistribute it and/or modify      * it under the terms of the GNU General Public License as      * published by the Free Software Foundation; either            * version 2 of the License, or (at your option)                * any later version.                                           *                                                              * The GNU General Public License can be found at               * http://www.gnu.org/copyleft/gpl.html.                        *                                                              * This script is distributed in the hope that it will be       * useful, but WITHOUT ANY WARRANTY; without even the implied   * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR      * PURPOSE. See the GNU General Public License for more         * details.                                                     *                                                              * This copyright notice MUST APPEAR in all copies of the       * script!                                                      ***************************************************************/';
        foreach ($tokens as $token) {
            // Looking for the first occurence of a comment token
            if ($token['type'] === 'T_COMMENT') {
                $keyword = $tokens[$currentPtr + 1]['content'];
                if (trim($keyword) !== '* Copyright notice') {
                    $error = 'No copyright notice found; expected a notice like: ' . $copyrightNotice;
                    $phpcsFile->addWarning($error, $currentPtr, 'NoCopyrightNotice');
                }
                // After we found the first comment we break up. We don«t care
                // for other comments because the copyright notice should be the very next
                // after "<?php"-tag
                break;
            }
            $currentPtr++;
        }
    } // end progess()
    
}
?>