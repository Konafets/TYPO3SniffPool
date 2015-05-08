<?php
/**
 * Warns about the use of debug code like
 * print_r(), var_dump(), xdebug, debug and GeneralUtility::debug.
 *
 * PHP version 5
 *
 * @category  Debug
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\Debug;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Warns about the use of debug code like
 * print_r(), var_dump(), xdebug, debug and GeneralUtility::debug.
 *
 * @category  Debug
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class DebugCodeSniff implements Sniff
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
                T_STRING,
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
        $tokens       = $phpcsFile->getTokens();
        $tokenType    = $tokens[$stackPtr]['type'];
        $currentToken = $tokens[$stackPtr]['content'];
        switch ($tokenType) {
        case 'T_STRING':
            if ($currentToken === 'debug') {
                $previousToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
                if ($tokens[$previousToken]['content'] === '::') {
                    $errorData = array($tokens[($previousToken - 1)]['content']);
                    $error     = 'Call to debug function %s::debug() must be removed';
                    $phpcsFile->addError($error, $stackPtr, 'StaticDebugCall', $errorData);
                } else if (($tokens[$previousToken]['content'] === '->') || ($tokens[$previousToken]['content'] === 'class') || ($tokens[$previousToken]['content'] === 'function')) {
                    // We don't care about code like:
                    // if ($this->debug) {...}
                    // class debug {...}
                    // function debug () {...}.
                    return;
                } else {
                    $errorData = array($currentToken);
                    $error     = 'Call to debug function %s() must be removed';
                    $phpcsFile->addError($error, $stackPtr, 'DebugFunctionCall', $errorData);
                }
            } else if ($currentToken === 'print_r' || $currentToken === 'var_dump' || $currentToken === 'xdebug') {
                $errorData = array($currentToken);
                $error     = 'Call to debug function %s() must be removed';
                $phpcsFile->addError($error, $stackPtr, 'NativDebugFunction', $errorData);
            }//end if
            break;
        case 'T_COMMENT':
            $comment          = $tokens[$stackPtr]['content'];
            $ifDebugInComment = preg_match_all('/\b((DebugUtility::)?([x]?debug)|(print_r)|(var_dump))([\s]+)?\(/', $comment, $matchesArray);
            if ($ifDebugInComment === 1) {
                $error  = 'Its not enough to comment out debug functions calls; ';
                $error .= 'they must be removed from code.';
                $phpcsFile->addError($error, $stackPtr, 'CommentOutDebugCall');
            }
            break;
        default:
        }//end switch

    }//end process()


}//end class
