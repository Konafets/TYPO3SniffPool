<?php
/**
 * TYPO3_Sniffs_WhiteSpace_DisallowSpaceIndentSniff.
 *
 * PHP version 5
 * TYPO3 CMS
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Checks that code is indent with tabs; spaces are not allowed.
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_WhiteSpace_DisallowSpaceIndentSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_OPEN_TAG);

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
        $tokens = $phpcsFile->getTokens();
        // Make sure this is the first open tag.
        $previousOpenTag = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
        if ($previousOpenTag !== false) {
            return;
        }

        $tokenCount         = 0;
        $currentLineContent = '';
        $currentLine        = 1;
        $tokenIsDocComment  = true;
        $tokenIsString      = true;
        foreach ($tokens as $token) {
            $tokenCount++;
            if ($token['line'] === $currentLine) {
                $currentLineContent .= $token['content'];
            } else {
                $currentLineContent = trim($currentLineContent, $phpcsFile->eolChar);
                $this->ifSpaceIndent(
                    $phpcsFile,
                    ($tokenCount - 1),
                    $currentLineContent,
                    $tokenIsDocComment,
                    $tokenIsString
                );

                $currentLineContent = $token['content'];
                // We have to check if the current token is a comment.
                // We are looking for doc comments and normal comments
                // but by the architecture comments like ...
                // "// comment" will be ignored.
                if (preg_match('/^T_(DOC_)?COMMENT(_WHITESPACE)?$/', $token['type']) === 1) {
                    $tokenIsDocComment = true;
                } else {
                    $tokenIsDocComment = false;
                }

                if (preg_match('/^T_CONSTANT_ENCAPSED_STRING$/', $token['type']) === 1) {
                    $tokenIsString = true;
                } else {
                    $tokenIsString = false;
                }

                $currentLine++;
            }//end if
        }//end foreach

        $this->ifSpaceIndent($phpcsFile, ($tokenCount - 1), $currentLineContent, (bool) $tokenIsDocComment, (bool) $tokenIsString);
        return;

    }//end process()


    /**
     * Check if the code is intend with spaces
     *
     * @param PHP_CodeSniffer_File $phpcsFile         The file being scanned.
     * @param int                  $stackPtr          The token at the end of
     *                                                the line.
     * @param string               $lineContent       The content of the line.
     * @param boolean              $tokenIsDocComment True if the token is a
     *                                                doc block comment.
     *                                                False otherwise
     * @param boolean              $tokenIsString     True if the token is a string.
     *                                                False otherwise.
     *
     * @return void
     */
    protected function ifSpaceIndent(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $lineContent, $tokenIsDocComment, $tokenIsString)
    {
        // Is the line intent by something?
        if (preg_match('/(^\S)|(^\s\*)|(^$)/', $lineContent) === 1) {
            $hasIndention = false;
        } else {
            $hasIndention = true;
        }

        $indentionPart = '';
        if ($hasIndention === true) {
             // Spaces in strings at line start are allowed, so we don't care about.
            if ($tokenIsString === true) {
                return;
            }

            if ($tokenIsDocComment === true) {
                $indentionPart = (string) substr($lineContent, 0, strpos($lineContent, ' *'));
            } else {
                // Get the intention part of the line (is stored in $matches).
                preg_match_all('/^\s+/', $lineContent, $matches);
                $indentionPart = $matches[0][0];
            }

            // Is a space char in the indention?
            if (preg_match('/[^\t]/', $indentionPart) === 1) {
                $isSpace = true;
            } else {
                $isSpace = false;
            }

            if ($isSpace === true) {
                $error = 'Tabs must be used to indent lines; spaces are not allowed';
                $phpcsFile->addError(
                    $error,
                    ($stackPtr - 1),
                    'SpaceIndentionNotAllowed'
                );
            }
        }//end if

    }//end ifSpaceIndent()


}//end class
