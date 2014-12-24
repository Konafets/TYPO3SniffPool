<?php
/**
 * TYPO3_Sniffs_WhiteSpace_AsteriksWhitespacesSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Checks that one whitespace is after an asteriks charakter in comments
 *
 * Correct:   * @author Laura Thewalt
 * Incorrect: *@author Laura Thewalt
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_WhiteSpace_AsteriksWhitespacesSniff implements PHP_CodeSniffer_Sniff
{
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
    public function register()
    {
        return array(T_DOC_COMMENT_STAR);
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
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $contentAfterCommentStar = $tokens[$stackPtr + 1]['content'];
        // We ignore empty lines in doc comment
        if ($contentAfterCommentStar === "\n") {
            return;
        }

        if (strpos($contentAfterCommentStar, ' ') === false) {
            $error = 'Whitespace must be added after comment star sign; ';
            $error .= 'Expected "* %s", but found "*%s"';
            $data = array($contentAfterCommentStar, $contentAfterCommentStar);
            $fix = $phpcsFile->addFixableError(
                $error,
                $stackPtr,
                'NoWhitespaceAfterCommentStar',
                $data
            );

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->replaceToken($stackPtr, '* ');
                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}