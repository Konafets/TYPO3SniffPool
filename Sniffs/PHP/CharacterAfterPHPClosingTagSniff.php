<?php
/**
 * TYPO3_Sniffs_PHP_CharacterAfterPHPClosingTagSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  PHP
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that after php closing tag is no other char like newline.
 *
 * @category  PHP
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_PHP_CharacterAfterPHPClosingTagSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLOSE_TAG);
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
        $numberOfAllTokens = count($tokens) - 1;
        $diffCurrentTokenToAllTokens = $numberOfAllTokens - $stackPtr;
        if ($keyword) {
            if ($keyword === '?>' . $phpcsFile->eolChar) {
                $error = 'No newline character is allowed after php closing tag; expect " ?> " but found " ?>\n " ';
                $phpcsFile->addError($error, $stackPtr, 'NoNewlineCharAfterPHPClosingTag');
            } elseif ($diffCurrentTokenToAllTokens !== 0) {
                $nextToken = $tokens[$stackPtr + 1]['content'];
                $error = 'No character is allowed after php closing tag; expect " ?> " but found " ?>' . $nextToken . ' " ';
                $phpcsFile->addError($error, $stackPtr, 'NoCharacterAfterPHPClosingTag');
            }
        }
    }
}
?>