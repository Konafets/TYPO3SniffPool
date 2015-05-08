<?php
/**
 * Checks that single line comments (//) are in a new line.
 *
 * PHP version 5
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Checks that single line comments (//) are in a new line.
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class DoubleSlashCommentsInNewLineSniff implements Sniff
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
        $tokens  = $phpcsFile->getTokens();
        $keyword = $tokens[$stackPtr]['content'];
        if (substr($keyword, 0, 2) === '//' && $this->existsOtherCodeBeforeThisComment($tokens, $stackPtr) === true) {
            $error = 'The double slash comments must be on a seperate line.';
            $phpcsFile->addError($error, $stackPtr);
        }

    }//end process()


    /**
     * Checks if the found T_COMMENT is in a line which available source code.
     * Returns true, if there IS existing source code in the same line before
     * the comment.
     *
     * $a = $b; // This is the found comment
     * => Returns true
     *
     * // This is the found comment
     * => Returns false
     *
     * @param array $tokens   Token array with all tokens from the file which
     *                        is checked
     * @param int   $stackPtr Stackpointer where one of the registered token
     *                        was found
     *
     * @return bool
     */
    protected function existsOtherCodeBeforeThisComment(array $tokens, $stackPtr)
    {
        $result       = false;
        $originalLine = $tokens[$stackPtr]['line'];
        do {
            $stackPtr--;
            $line = $tokens[$stackPtr]['line'];
            if ($originalLine === $line && $tokens[$stackPtr]['type'] !== 'T_WHITESPACE') {
                $result = true;
            }
        } while ($result == false && $originalLine == $line);
        return $result;

    }//end existsOtherCodeBeforeThisComment()


}//end class
