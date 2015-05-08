<?php
/**
 * Checks that the PHP function "each()" is not used in loop conditions.
 *
 * PHP version 5
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\ControlStructures;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Checks that the PHP function "each()" is not used in loop conditions.
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class DisallowEachInLoopConditionSniff implements Sniff
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

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens     = $phpcsFile->getTokens();
        $startToken = ($tokens[$stackPtr]['parenthesis_opener'] + 1);
        $endToken   = ($tokens[$stackPtr]['parenthesis_closer'] - 1);
        $result     = $phpcsFile->findNext(T_STRING, $startToken, $endToken, false, 'each');
        if ($result !== false) {
            $message = 'Usage of "each()" not allowed in loop condition. Use "foreach"-loop instead.';
            $phpcsFile->addError($message, $stackPtr, 'EachInWhileLoopNotAllowed');
        }

    }//end process()


}//end class
