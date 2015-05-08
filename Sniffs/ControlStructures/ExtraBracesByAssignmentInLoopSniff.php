<?php
/**
 * Checks that all assignments in loop conditions uses extra braces.
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
 * Checks that all assignments in loop conditions uses extra braces.
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class ExtraBracesByAssignmentInLoopSniff implements Sniff
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
        return array(
                T_WHILE,
                T_IF,
               );

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
        $tokens           = $phpcsFile->getTokens();
        $parenthesisStart = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr);
        $parenthesisStart = $tokens[$parenthesisStart]['parenthesis_opener'];
        $parenthesisEnd   = $tokens[$parenthesisStart]['parenthesis_closer'];

        $equalOperator = $phpcsFile->findNext(T_EQUAL, $parenthesisStart, $parenthesisEnd);
        if ($equalOperator === false) {
            return;
        }

        $braceBefore = $phpcsFile->findPrevious(T_OPEN_PARENTHESIS, $equalOperator, $parenthesisStart);
        if ($braceBefore === $parenthesisStart) {
            $message = 'Assignments in condition should be surrounded by the extra pair of brackets';
            $phpcsFile->addError($message, $stackPtr, 'AssignmentsInCondition');
        }

    }//end process()


}//end class
