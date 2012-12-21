<?php
/**
 * TYPO3_Sniffs_ControlStructures_DisallowEachInLoopConditionSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that the PHP function "each()" is not used in loop conditions.
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_ControlStructures_DisallowEachInLoopConditionSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP');

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_WHILE);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $startToken = $tokens[$stackPtr]['parenthesis_opener'] + 1;
        $endToken = $tokens[$stackPtr]['parenthesis_closer'] - 1;
        $result = $phpcsFile->findNext(T_STRING, $startToken, $endToken, false, 'each');
        if ($result !== false) {
            $message = 'Usage of "each()" not allowed in loop condition. Use "foreach"-loop instead.';
            $phpcsFile->addError($message, $stackPtr, 'EachInWhileLoopNotAllowed');
        }
    }
}
?>