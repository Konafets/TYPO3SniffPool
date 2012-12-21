<?php
/**
 * TYPO3_Sniffs_Strings_UnnecessaryStringConcatSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Strings
 * @package   TYPO3_PHPCS_Pool
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.typo3.org
 */
/**
 * TYPO3_Sniffs_Strings_UnnecessaryStringConcatSniff.
 *
 * Checks that two strings are not concatenated together; suggests
 * using one string instead.
 *
 * @category  Strings
 * @package   TYPO3_PHPCS_Pool
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Strings_UnnecessaryStringConcatSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP', 'JS',);

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    public $error = true;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING_CONCAT, T_PLUS,);
    }

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // Work out which type of file this is for.
        $tokens = $phpcsFile->getTokens();
        if ($tokens[$stackPtr]['code'] === T_STRING_CONCAT) {
            if ($phpcsFile->tokenizerType === 'JS') {
                return;
            }
        } else {
            if ($phpcsFile->tokenizerType === 'PHP') {
                return;
            }
        }
        $prev = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        $columnPrevToken = $tokens[$prev]['column'];
        $columnCurrentToken = $tokens[$stackPtr]['column'];
        $lineCurrentToken = $tokens[$stackPtr]['line'];
        $lineNextToken = $tokens[$next]['line'];
        // concat string over multiple lines are allowed, so we leave the sniff here
        if ($lineCurrentToken !== $lineNextToken) {
            return;
        }
        if ($prev === false || $next === false) {
            return;
        }
        // check if the concatenation operator its on the end of the line,
        // otherwise we trow an error
        if ($columnPrevToken > $columnCurrentToken) {
            $error = 'Line concatenation operator must be at the end of the line';
            $phpcsFile->addError($error, $stackPtr, 'Found');
            return;
        }
        $stringTokens = PHP_CodeSniffer_Tokens::$stringTokens;
        if (in_array($tokens[$prev]['code'], $stringTokens) === true && in_array($tokens[$next]['code'], $stringTokens) === true) {
            if ($tokens[$prev]['content'][0] === $tokens[$next]['content'][0]) {
                $error = 'String concat is not required here; use a single string instead';
                if ($this->error === true) {
                    $phpcsFile->addError($error, $stackPtr, 'Found');
                } else {
                    $phpcsFile->addWarning($error, $stackPtr, 'Found');
                }
            }
        }
    }
}
?>