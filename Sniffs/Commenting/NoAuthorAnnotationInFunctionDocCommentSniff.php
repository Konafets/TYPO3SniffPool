<?php
/**
 * TYPO3_Sniffs_Commenting_NoAuthorAnnotationInFunctionDocCommentSniff
 *
 * PHP version 5
 * TYPO3 CMS
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

/**
 * Parses and verifies that a function / method doc comment
 * has no @author annotation.
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_Commenting_NoAuthorAnnotationInFunctionDocCommentSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * The function comment parser for the current method.
     *
     * @var PHP_CodeSniffer_Comment_Parser_FunctionCommentParser
     */
    protected $commentParser = null;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_DOC_COMMENT_TAG);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $content = $tokens[$stackPtr]['content'];

        if ($content === '@author') {
            $type = 'NoAuthorAnnotationInFunctionDocComment';
            $error = '@author tag should not be used in function or method phpDoc comment blocks - only at class level';
            $fix = $phpcsFile->addFixableError($error, $stackPtr, $type);

            if ($fix === true) {
                $lineStart = $phpcsFile->findPrevious(T_DOC_COMMENT_WHITESPACE, $stackPtr, null, false, "\t ");
                $lineEnd = $phpcsFile->findNext(T_DOC_COMMENT_WHITESPACE, $stackPtr + 1, null, false, $phpcsFile->eolChar);

                $phpcsFile->fixer->beginChangeset();
                $i = $lineStart;
                for ($i;$i <= $lineEnd;$i++) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }
                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}