<?php
/**
 * TYPO3_Sniffs_ControlStructures_ValidDefaultStatementsInSwitchesSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that there is a default case after all other cases in switch statement.
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_ControlStructures_ValidDefaultStatementsInSwitchesSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_SWITCH);
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
        $nextDefaultID = $phpcsFile->findNext(T_DEFAULT, $stackPtr + 1);
        $nextDefault = $tokens[$nextDefaultID];
        if (array_key_exists($stackPtr, $nextDefault['conditions']) === false) {
            $phpcsFile->addError('Expect one default case in the switch statement; but found zero.', $stackPtr);
        }
    }
}
?>