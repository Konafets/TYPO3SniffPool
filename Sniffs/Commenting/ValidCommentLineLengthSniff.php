<?php
/**
 * Checks the length of comments.
 *
 * PHP version 5
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Laura Thewalt
 * @copyright 2014 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Checks the length of comments.
 *
 * Comment lines should be kept within a limit of about 80 characters
 * (excluding tabs)
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Laura Thewalt
 * @copyright 2014 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class ValidCommentLineLengthSniff implements Sniff
{
    /**
     * Max character length of comments
     *
     * @var int
     */
    public $maxCommentLength = 80;

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
        return array(
                T_DOC_COMMENT_STAR,
                T_COMMENT,
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
        $tokens      = $phpcsFile->getTokens();
        $commentLine = $tokens[$stackPtr]['content'];
        $lineEnd     = $phpcsFile->findNext(T_DOC_COMMENT_WHITESPACE, ($stackPtr + 1), null, false, $phpcsFile->eolChar);

        for ($i = ($stackPtr + 1); $i < $lineEnd; $i++) {
            $commentLine .= $tokens[$i]['content'];
        }

        $commentLength = strlen($commentLine);

        if ($commentLength > $this->maxCommentLength) {
            $phpcsFile->addWarning('Comment lines should be kept within a limit of about '.$this->maxCommentLength.' characters but this comment has '.$commentLength.' character!', $stackPtr);
        }

    }//end process()


}//end class
