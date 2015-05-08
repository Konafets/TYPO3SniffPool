<?php
/**
 * Checks the indent of comments.
 *
 * PHP version 5
 *
 * @category  Whitespace
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\WhiteSpace;

use \PHP_CodeSniffer\Sniffs\Sniff;
use \PHP_CodeSniffer\Files\File;

/**
 * Checks the indent of comments.
 *
 * @category  Whitespace
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class WhitespaceAfterCommentSignsSniff implements Sniff
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
        $tokens      = $phpcsFile->getTokens();
        $commentLine = '';
        // We only need the single line comments which started with double slashes.
        if (substr_compare($tokens[$stackPtr]['content'], '//', 0, 2) === 0) {
            $commentLine = $tokens[$stackPtr]['content'];
            if (preg_match_all('/\/\/( ){1,}[\S]|(\/){3,}/', $commentLine, $matchesarray) === 0) {
                $phpcsFile->addError('Whitespace must be added after double slashes in single line comments; expected "// This is a comment" but found "'.trim($tokens[$stackPtr]['content']).'"', $stackPtr);
            }
        }

    }//end process()


}//end class
