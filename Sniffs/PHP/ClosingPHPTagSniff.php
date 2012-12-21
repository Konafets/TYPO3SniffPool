<?php
/**
 * TYPO3_Sniffs_PHP_ClosingPHPTagSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  PHP
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that the file does not end with a closing tag.
 *
 * @category  PHP
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_PHP_ClosingPHPTagSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);
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
        // Get the php close tag or FALSE if not found
        $closeTag = $phpcsFile->findNext(T_CLOSE_TAG, $stackPtr);
        // Get the ID of the last token array by finding out how many
        // arrays are in the stack and decrease this amount of one
        $lastTokenInStackID = count($tokens) - 1;
        if ($closeTag === false) {
            $error = 'A closing PHP tag is required at the end of a PHP file';
            $phpcsFile->addError($error, $lastTokenInStackID, 'NoClosingPHPTag');
        }
    }
}
?>