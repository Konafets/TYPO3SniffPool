<?php
/**
 * TYPO3_Sniffs_Commenting_DoubleSlashCommentsInNewLineSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that single line comments (//) are in a new line.
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Commenting_DoubleSlashCommentsInNewLineSniff implements PHP_CodeSniffer_Sniff
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
        $keyword = $tokens[$stackPtr]['content'];
        if (substr($keyword, 0, 2) === '//' && $this->existsOtherCodeBeforeThisComment($tokens, $stackPtr)) {
            $error = 'The double slash comments must be on a seperate line.';
            $phpcsFile->addError($error, $stackPtr);
        }
    }

    /**
     * Checks if the found T_COMMENT is in a line which available source code.
     * Returns true, if there IS existing source code in the same line before the comment.
     *
     * e.g.
     * $a = $b; // This is the found comment
     * => Returns true
     *
     * // This is the found comment
     * => Returns false
     *
     * @param array $tokens   Token arry with all tokens from the file which is checked
     * @param int   $stackPtr Stackpointer where one of the registered token was found
     *
     * @return bool
     */
    protected function existsOtherCodeBeforeThisComment(array $tokens, $stackPtr)
    {
        $result = false;
        $originalLine = $tokens[$stackPtr]['line'];
        do {
            $stackPtr--;
            $line = $tokens[$stackPtr]['line'];
            if ($originalLine == $line && $tokens[$stackPtr]['type'] != 'T_WHITESPACE') {
                $result = true;
            }
        } while ($result == false && $originalLine == $line);
        return $result;
    }
}
?>