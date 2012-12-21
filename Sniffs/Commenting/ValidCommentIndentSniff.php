<?php
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
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks the indent of comments
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Commenting_ValidCommentIndentSniff implements PHP_CodeSniffer_Sniff
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
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if (substr($tokens[$stackPtr]['content'], 0, 2) === '//') {
            $commentColumn = ($tokens[$stackPtr]['column'] - 1);
            $nextNonCommentToken = $phpcsFile->findNext(array(T_COMMENT, T_DOC_COMMENT, T_WHITESPACE), $stackPtr, null, true);
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