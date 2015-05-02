<?php
/**
 * TYPO3_Sniffs_WhiteSpace_NoWhitespaceAtInDecrementSniff.
 *
 * PHP version 5
 *
 * @category  Whitespace
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Andy Grunwald
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Checks that no whitespace is before postfix and after prefix
 * increment or decrement operators.
 *
 * @category  Whitespace
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Andy Grunwald
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_WhiteSpace_NoWhitespaceAtInDecrementSniff implements PHP_CodeSniffer_Sniff
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
        return array(
                T_INC,
                T_DEC,
               );

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
        $found       = $this->getFoundString($tokens, $stackPtr);
        $kindOfToken = $this->getKindOfToken(
            $tokens[$stackPtr],
            $phpcsFile,
            $stackPtr
        );

        $prevStopToken = $phpcsFile->findPrevious(array(T_EQUAL, T_SEMICOLON), $stackPtr, null, false, null, true);
        $prev          = $phpcsFile->findPrevious(T_VARIABLE, ($stackPtr - 1), $prevStopToken, false, null, true);
        $next          = $phpcsFile->findNext(T_VARIABLE, ($stackPtr + 1), null, false, null, true);

        switch ($kindOfToken) {
        case 'postfix':
            if ($this->existsWhitespace('before', $tokens, $stackPtr) === true) {
                $error = 'No whitespace before the %s operator allowed. Found "%s". Expected "%s"';
                $code  = 'NoWhitSpaceBeforePostfix';
                $found = rtrim($found);
                $data  = array(
                          $kindOfToken,
                          $tokens[$prev]['content'].$found,
                          $tokens[$prev]['content'].trim($found),
                         );

                $fix = $phpcsFile->addFixableWarning($error, $stackPtr, $code, $data);

                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken(($stackPtr - 1), '');
                }
            }
            break;

        case 'prefix':
            if ($this->existsWhitespace('after', $tokens, $stackPtr) === true) {
                $error = 'No whitespace after the %s operator allowed. Found "%s". Expected "%s"';
                $code  = 'NoWhitSpaceAfterPrefix';
                $found = ltrim($found);
                $data  = array(
                          $kindOfToken,
                          $found.$tokens[$next]['content'],
                          trim($found).$tokens[$next]['content'],
                         );

                $fix = $phpcsFile->addFixableWarning($error, $stackPtr, $code, $data);

                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken(($stackPtr + 1), '');
                }
            }
            break;

        default:
        }//end switch

    }//end process()


    /**
     * Returns the kind of token.
     * Possible values: assignment, arithmetic, comparison
     *
     * @param array                $token     All tokens of the current file
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return string
     */
    protected function getKindOfToken(array $token, PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $result = '';
        if (in_array($token['code'], $this->register()) === true) {
            $prevStopToken = $phpcsFile->findPrevious(array(T_EQUAL, T_SEMICOLON), $stackPtr, null, false, null, true);
            $prev          = $phpcsFile->findPrevious(T_VARIABLE, ($stackPtr - 1), $prevStopToken, false, null, true);
            $next          = $phpcsFile->findNext(T_VARIABLE, ($stackPtr + 1), null, false, null, true);

            if ($prev !== false) {
                $result = 'postfix';
            } else if ($next !== false) {
                $result = 'prefix';
            }
        }

        return $result;

    }//end getKindOfToken()


    /**
     * Returns the found string.
     * The current $stackPtr position + 1 before + 1 after.
     *
     * @param array $tokens   All tokens of the current file.
     * @param int   $stackPtr Stack pointer where token was found. Position in $token
     *
     * @return string
     */
    protected function getFoundString(array $tokens, $stackPtr)
    {
        $found = $tokens[($stackPtr - 1)]['content'].$tokens[$stackPtr]['content'].$tokens[($stackPtr + 1)]['content'];
        return $found;

    }//end getFoundString()


    /**
     * Checks if there is whitespace before or after the incomming $stackPts
     *
     * @param string $mode     Mode which will be checked. Whitespace before or
     *                         after. Possible values: before, after
     * @param array  $tokens   Array of all tokens of the current file
     * @param int    $stackPtr Current position in $tokens
     *
     * @return bool
     */
    protected function existsWhitespace($mode, array $tokens, $stackPtr)
    {
        $result   = false;
        $stackPtr = $this->manageStackPtrCounter($mode, $stackPtr);
        if ($tokens[$stackPtr]['code'] === T_WHITESPACE) {
            $result = true;
        }

        return $result;

    }//end existsWhitespace()


    /**
     * Increments or decrements the incomming stack pointer (depends on $mode).
     *
     * @param string $mode     Mode to decide if +1 or -1 with $stackPtr.
     *                         Possible values: before, after
     * @param int    $stackPtr Stack pointer which will be increment or decrement
     *
     * @return int
     */
    protected function manageStackPtrCounter($mode, $stackPtr)
    {
        switch (strtolower($mode)) {
        case 'before':
            $stackPtr--;
            break;
        case 'after':
            $stackPtr++;
            break;
        default:
        }

        return $stackPtr;

    }//end manageStackPtrCounter()


}//end class
