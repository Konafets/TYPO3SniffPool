<?php
/**
 * TYPO3SniffPool_Sniffs_ControlStructures_UnusedVariableInForEachLoopSniff.
 *
 * PHP version 5
 * TYPO3CMS
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Checks if a unused variable in a foreach loop is named $_.
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_ControlStructures_UnusedVariableInForEachLoopSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_FOREACH);

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
        $tokens     = $phpcsFile->getTokens();
        $startToken = $tokens[$stackPtr]['parenthesis_opener'];
        $endToken   = $tokens[$stackPtr]['parenthesis_closer'];
        $startToken = $phpcsFile->findNext(T_AS, $startToken, $endToken);

        $valueToken = $phpcsFile->findNext(T_VARIABLE, $startToken, $endToken);
        $tmpToken   = $phpcsFile->findNext(T_VARIABLE, ($valueToken + 1), $endToken);

        // If $tmpToken is not false, the foreach loop uses $key => $value.
        $keyToken = false;
        if ($tmpToken !== false) {
            $keyToken   = $valueToken;
            $valueToken = $tmpToken;
            unset($tmpToken);
        }

        // If there are no scope_opener & scope_closer, the developer uses no parenthesis for the loop, like
        //      foreach($myArray as $key => $value)
        //          echo $key . ' - ' . $value;
        //
        // In this case we have to determine the next statement / scope_opener & scope_closer on our own.
        if (array_key_exists('scope_opener', $tokens[$stackPtr]) === false
            && array_key_exists('scope_closer', $tokens[$stackPtr]) === false) {

            $scopeOpener = $phpcsFile->findNext([T_WHITESPACE], ($endToken + 1), null, true, null, true);
            $scopeCloser = $phpcsFile->findEndOfStatement($scopeOpener);

        } else {
            $scopeOpener = $tokens[$stackPtr]['scope_opener'];
            $scopeCloser = $tokens[$stackPtr]['scope_closer'];
        }

        // If a $key is used in foreach loop but not used in the foreach body.
        if ($keyToken !== false && $phpcsFile->findNext(T_VARIABLE, $scopeOpener, $scopeCloser, false, $tokens[$keyToken]['content']) === false) {
            $message = 'The usage of the key variable %s is not necessary. Please remove this.';
            $phpcsFile->addError($message, $stackPtr, 'KeyVariableNotNecessary', array($tokens[$keyToken]['content']));
        }

        // If the $value is named $_ AND used in the foreach body, this variable has to be renamed.
        if ($tokens[$valueToken]['content'] === '$_' && $phpcsFile->findNext(T_VARIABLE, $scopeOpener, $scopeCloser, false, $tokens[$valueToken]['content']) !== false) {
            $message = 'The variable $_ is used in the foreach body. Please rename this variable to a more useful name.';
            $phpcsFile->addError($message, $stackPtr, 'ValueVariableWrongName');

            // If the $value is NOT named $_, but no one will use this in the foreach body, this variable has to be renamed.
        } else if ($tokens[$valueToken]['content'] !== '$_' && $phpcsFile->findNext(T_VARIABLE, $scopeOpener, $scopeCloser, false, $tokens[$valueToken]['content']) === false) {
            $message = 'The variable %s is NOT used in the foreach body. Please rename this variable to $_.';
            $phpcsFile->addError($message, $stackPtr, 'ValueVariableNotUsed', array($tokens[$valueToken]['content']));
        }

    }//end process()


}//end class
