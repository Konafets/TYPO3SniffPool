<?php
/**
 * TYPO3_Sniffs_ControlStructures_ExtraBracesByAssignmentInLoopSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Checks that all assignments in loop conditions uses extra braces.
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_ControlStructures_ExtraBracesByAssignmentInLoopSniff implements PHP_CodeSniffer_Sniff
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
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
