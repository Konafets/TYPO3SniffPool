<?php
/**
 * Makes sure there are no spaces between the concatenation operator (.) and
 * the strings being concatenated.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\Strings;

use \PHP_CodeSniffer\Sniffs\Sniff;
use \PHP_CodeSniffer\Files\File;

/**
 * Makes sure there are no spaces between the concatenation operator (.) and
 * the strings being concatenated.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class ConcatenationSpacingSniff implements Sniff
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
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *                                        token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $prevToken = $tokens[($stackPtr - 1)];
        $nextToken = $tokens[($stackPtr + 1)];

        if ($prevToken['code'] !== T_WHITESPACE
            || $nextToken['code'] !== T_WHITESPACE
        ) {
            $error = 'Concat operator must be surrounded by spaces. ';
            $phpcsFile->addError($error, $stackPtr, 'NoSpaceAroundConcat');
        }

        if (($prevToken['code'] === T_WHITESPACE && stristr($prevToken['content'], '  ') !== false)
            || ($nextToken['code'] === T_WHITESPACE && stristr($nextToken['content'], '  ') !== false)
        ) {
            $error = 'Concat operator should be surrounded by just one space';
            $phpcsFile->addWarning($error, $stackPtr, 'OnlyOneSpaceAroundConcat');
        }

    }//end process()


}//end class
