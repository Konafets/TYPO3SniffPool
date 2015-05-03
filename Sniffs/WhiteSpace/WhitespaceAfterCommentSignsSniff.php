<?php
/**
 * TYPO3_Sniffs_WhiteSpace_WhitespaceAfterCommentSignsSniff.
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
/**
 * Checks the indent of comments
 *
 * @category  Whitespace
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_WhiteSpace_WhitespaceAfterCommentSignsSniff implements PHP_CodeSniffer_Sniff
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
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
