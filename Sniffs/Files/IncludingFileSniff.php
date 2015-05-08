<?php
/**
 * Checks that the include_once is used in all cases.
 *
 * PHP version 5
 *
 * @category  Files
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\Files;

use PHP_CodeSniffer\Sniffs\Sniff;
use \PHP_CodeSniffer\Files\File;

/**
 * Checks that the include_once is used in all cases.
 *
 * @category  Files
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class IncludingFileSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_INCLUDE_ONCE,
                T_REQUIRE,
                T_INCLUDE,
               );

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $keyword   = $tokens[$stackPtr]['content'];
        $tokenCode = $tokens[$stackPtr]['type'];
        switch ($tokenCode) {
        case 'T_INCLUDE_ONCE':
                // Here we are looking if the found include_once keyword is
                // part of an XClass declaration where this is allowed.
            if ($tokens[($stackPtr + 7)]['content'] === "'XCLASS'") {
                return;
            }

            $error  = 'Including files with "'.$keyword.'" is not allowed; ';
            $error .= 'use "require_once" instead';
            $phpcsFile->addError($error, $stackPtr);
            break;
        case 'T_REQUIRE':
            $error  = 'Including files with "'.$keyword.'" is not allowed; ';
            $error .= 'use "require_once" instead';
            $phpcsFile->addError($error, $stackPtr);
            break;
        case 'T_INCLUDE':
            $error  = 'Including files with "'.$keyword.'" is not allowed; ';
            $error .= 'use "require_once" instead';
            $phpcsFile->addError($error, $stackPtr);
            break;
        default:
        }//end switch

    }//end process()


}//end class
