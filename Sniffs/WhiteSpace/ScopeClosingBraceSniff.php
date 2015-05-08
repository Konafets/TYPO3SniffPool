<?php
/**
 * Checks that the closing braces of scopes are aligned correctly.
 *
 * PHP version 5
 *
 * @category  WhiteSpace
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2013 Stefano Kowalke
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Checks that the closing braces of scopes are aligned correctly.
 *
 * @category  WhiteSpace
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2013 Stefano Kowalke
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class ScopeClosingBraceSniff implements Sniff
{

    /**
     * The number of spaces code should be indented.
     *
     * @var int
     */
    public $indent = 1;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return Tokens::$scopeOpeners;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // If this is an inline condition (ie. there is no scope opener), then
        // return, as this is not a new scope.
        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $scopeStart = $tokens[$stackPtr]['scope_opener'];
        $scopeEnd   = $tokens[$stackPtr]['scope_closer'];

        // If the scope closer doesn't think it belongs to this scope opener
        // then the opener is sharing its closer with other tokens. We only
        // want to process the closer once, so skip this one.
        if ($tokens[$scopeEnd]['scope_condition'] !== $stackPtr) {
            return;
        }

        // We need to actually find the first piece of content on this line,
        // because if this is a method with tokens before it (public, static etc)
        // or an if with an else before it, then we need to start the scope
        // checking from there, rather than the current token.
        $lineStart = ($stackPtr - 1);
        for ($lineStart; $lineStart > 0; $lineStart--) {
            if (strpos($tokens[$lineStart]['content'], $phpcsFile->eolChar) !== false) {
                break;
            }
        }

        // We found a new line, now go forward and find the first non-whitespace
        // token.
        $lineStart = $phpcsFile->findNext(
            array(T_WHITESPACE),
            ($lineStart + 1),
            null,
            true
        );

        $startColumn = $tokens[$lineStart]['column'];

        // Check that the closing brace is on it's own line.
        $lastContent = $phpcsFile->findPrevious(
            array(T_WHITESPACE),
            ($scopeEnd - 1),
            $scopeStart,
            true
        );

        if ($tokens[$lastContent]['line'] === $tokens[$scopeEnd]['line']) {
            $error = 'Closing brace must be on a line by itself';
            $phpcsFile->addError($error, $scopeEnd, 'Line');
            return;
        }

        // Check now that the closing brace is lined up correctly.
        $braceIndent = $tokens[$scopeEnd]['column'];
        if (in_array($tokens[$stackPtr]['code'], array(T_CASE, T_DEFAULT)) === true) {
            // BREAK statements should be indented one tab from the
            // CASE or DEFAULT statement.
            if ($braceIndent !== ($startColumn + $this->indent)) {
                $error = 'Case breaking statement indented incorrectly; expected %s tabs, found %s';
                $data  = array(
                          ($startColumn + $this->indent - 1),
                          ($braceIndent - 1),
                         );
                $phpcsFile->addError($error, $scopeEnd, 'BreakIdent', $data);
            }
        } else {
            if ($braceIndent !== $startColumn) {
                $error = 'Closing brace indented incorrectly; expected %s tabs, found %s';
                $data  = array(
                          ($startColumn - 1),
                          ($braceIndent - 1),
                         );
                $phpcsFile->addError($error, $scopeEnd, 'Indent', $data);
            }
        }//end if

    }//end process()


}//end class
