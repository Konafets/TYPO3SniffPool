<?php
/**
 * Parses and verifies that a function / method doc comment
 * has no @author annotation.
 *
 * PHP version 5
 *
 * @category Commenting
 * @package  TYPO3SniffPool
 * @author   Stefano Kowalke <blueduck@mailbox.org>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link     https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Parses and verifies that a function / method doc comment
 * has no @author annotation.
 *
 * @category Commenting
 * @package  TYPO3SniffPool
 * @author   Stefano Kowalke <blueduck@mailbox.org>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version  Release: @package_version@
 * @link     https://github.com/typo3-ci/TYPO3SniffPool
 */
class NoAuthorAnnotationInFunctionDocCommentSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_FUNCTION,
                T_CLASS,
                T_INTERFACE,
                T_TRAIT,
               );

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $type      = strtolower($tokens[$stackPtr]['content']);
        $errorData = array($type);

        $find   = Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);
        // If there is no comment we return here. This case will handled by another sniff.
        if ($commentEnd === 0 || $commentEnd === false) {
            return;
        }

        if (isset($tokens[$commentEnd]['comment_opener']) === false) {
            return;
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];
        $token        = $phpcsFile->findPrevious(T_DOC_COMMENT_TAG, $commentEnd, $commentStart, false, '@author');

        if ($type === 'function' && $token !== false) {
            $type  = 'NoAuthorAnnotationInFunctionDocComment';
            $error = 'The @author tag should not be used in %s doc comment blocks - only at class level';

            $fix = $phpcsFile->addFixableError($error, $stackPtr, $type, $errorData);

            if ($fix === true) {
                $lineStart = ($phpcsFile->findPrevious(T_DOC_COMMENT_WHITESPACE, $stackPtr, null, false, "\n") + 1);
                $lineEnd   = $phpcsFile->findNext(T_DOC_COMMENT_WHITESPACE, ($stackPtr + 1), null, false, $phpcsFile->eolChar);

                $phpcsFile->fixer->beginChangeset();
                $i = $lineStart;
                for ($i;$i <= $lineEnd;$i++) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->endChangeset();
            }
        } else if (in_array($type, array('class', 'interface', 'trait')) === true && $token === false) {
            $type  = 'NoAuthorAnnotationFoundInClassDocComment';
            $error = 'Please add a @author tag in %s doc comment blocks.';

            $phpcsFile->addWarning($error, $stackPtr, $type, $errorData);
        }//end if

    }//end process()


}//end class
