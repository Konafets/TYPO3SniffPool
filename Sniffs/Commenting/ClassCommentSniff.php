<?php
/**
 * Parses and verifies the class doc comment.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @copyright 2015 Stefano Kowalke
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Parses and verifies the class doc comment.
 *
 * This sniff is copied and modified from Generic_Sniffs_Commenting_ClassCommentSniff.
 * Thanks for this guys!
 *
 * Verifies that :
 * <ul>
 *  <li>A class doc comment exists.</li>
 *  <li>The comment starts with /**</li>
 *  <li>There is exactly one blank line before the class comment.</li>
 *  <li>There are no blank lines after the class comment.</li>
 * </ul>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @copyright 2015 Stefano Kowalke
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class ClassCommentSniff implements Sniff
{

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
    public function register()
    {
        return array(
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
        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
            && $tokens[$commentEnd]['code'] !== T_COMMENT
        ) {
            $phpcsFile->addError(
                'Missing %s doc comment',
                $stackPtr,
                'Missing',
                $errorData
            );
            $phpcsFile->recordMetric(
                $stackPtr,
                '%s has doc comment',
                'no'
            );
            return;
        } else {
            $phpcsFile->recordMetric(
                $stackPtr,
                '%s has doc comment',
                'yes'
            );
        }

        // Try and determine if this is a file comment instead of a class comment.
        // We assume that if this is the first comment after the open PHP tag, then
        // it is most likely a file comment instead of a class comment.
        if ($tokens[$commentEnd]['code'] === T_DOC_COMMENT_CLOSE_TAG) {
            $start = ($tokens[$commentEnd]['comment_opener'] - 1);
        } else {
            $start = $phpcsFile->findPrevious(
                T_COMMENT,
                ($commentEnd - 1),
                null,
                true
            );
        }

        $prev = $phpcsFile->findPrevious(T_WHITESPACE, $start, null, true);
        if ($tokens[$prev]['code'] === T_OPEN_TAG) {
            $prevOpen = $phpcsFile->findPrevious(T_OPEN_TAG, ($prev - 1));
            if ($prevOpen === false) {
                // This is a comment directly after the first open tag,
                // so probably a file comment.
                $phpcsFile->addError(
                    'Missing %s doc comment',
                    $stackPtr,
                    'Missing',
                    $errorData
                );
                return;
            }
        }

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            $phpcsFile->addError(
                'You must use "/**" style comments for a %s comment',
                $stackPtr,
                'WrongStyle',
                $errorData
            );
            return;
        }

        if ($tokens[$commentEnd]['line'] !== ($tokens[$stackPtr]['line'] - 1)) {
            $error = 'There must be no blank lines after the %s comment';
            $phpcsFile->addError($error, $commentEnd, 'SpacingAfter', $errorData);
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];
        if ($tokens[$prev]['line'] !== ($tokens[$commentStart]['line'] - 2)) {
            $error = 'There must be exactly one blank line before the %s comment';
            $phpcsFile->addError($error, $commentStart, 'SpacingBefore', $errorData);
        }

        // Class doc comments should provide an @author tag.
        $hasAuthorTag = false;
        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] === '@author') {
                $hasAuthorTag = true;
                break;
            }
        }

        if ($hasAuthorTag === false) {
            $phpcsFile->addWarning(
                'The doc comment on class level should provide an @author tag.',
                $commentStart,
                'NoAuthorTag'
            );
        }

    }//end process()


}//end class
