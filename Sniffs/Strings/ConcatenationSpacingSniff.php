<?php
/**
 * TYPO3_Sniffs_Strings_ConcatenationSpacingSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */

/**
 * TYPO3_Sniffs_Strings_ConcatenationSpacingSniff.
 *
 * Makes sure there are no spaces between the concatenation operator (.) and
 * the strings being concatenated.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Strings_ConcatenationSpacingSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING_CONCAT);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $prevToken = $tokens[($stackPtr - 1)];
        $nextToken = $tokens[($stackPtr + 1)];

        if ($prevToken['code'] !== T_WHITESPACE
            || $nextToken['code'] !== T_WHITESPACE
        ) {
            $error = 'Concat operator must be surrounded by spaces. ';
            $phpcsFile->addError($error, $stackPtr, 'NoSpaceAroundConcat');

        }

        if (($prevToken['code'] === T_WHITESPACE && stristr($prevToken['content'], '  ') !== FALSE)
            || ($nextToken['code'] === T_WHITESPACE && stristr($nextToken['content'], '  ') !== FALSE)
        ) {
            $error = 'Concat operator should be surrounded by just one space';
            $phpcsFile->addWarning($error, $stackPtr, 'OnlyOneSpaceAroundConcat');
        }
    }//end process()


}//end class

?>
