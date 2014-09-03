<?php
/**
 * TYPO3_Sniffs_WhiteSpace_AssignmentArithmeticAndComparisonSpaceSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Checks that one whitespace is before and after an assignment, arithmethic and comparison token.
 *
 * Correct:   $foo = $bar;
 * Incorrect: $foo=$bar;
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_WhiteSpace_AssignmentArithmeticAndComparisonSpaceSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of tokenizers this sniff supports
     *
     * @var array
     */
    public $supportedTokenizes = array('PHP');

    /**
     * The pre-/postfix tokens
     *
     * @var array
     */
    public $prePostFixTokens = array(T_INC, T_DEC);

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $registeredTokens = array_merge(PHP_CodeSniffer_Tokens::$assignmentTokens, PHP_CodeSniffer_Tokens::$arithmeticTokens);
        $registeredTokens = array_merge($registeredTokens, PHP_CodeSniffer_Tokens::$comparisonTokens);
        $registeredTokens = array_merge($registeredTokens, $this->prePostFixTokens);
        return $registeredTokens;
    }

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
        $tokens = $phpcsFile->getTokens();
        $found = $this->getFoundString($tokens, $stackPtr);
        $kindOfToken = $this->getKindOfToken($tokens[$stackPtr], $phpcsFile, $stackPtr);

        // The following code snippet was copied and modified from Squiz_Sniffs_Formatting_OperatorBracketSniff
        // Thanks for this guys!
        // There is one instance where brackets aren't needed, which involves
        // the minus sign being used to assign a negative number to a variable.
        if ($tokens[$stackPtr]['code'] === T_MINUS) {
            // Check to see if we are trying to return -n or if we are inside a ternary operator.
            $prev = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);

            if ($tokens[$prev]['code'] === T_RETURN
                || $tokens[$prev]['code'] === T_INLINE_THEN
                || $tokens[$prev]['code'] === T_INLINE_ELSE
            ) {
                return;
            }

            $number = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
            if ($tokens[$number]['code'] === T_LNUMBER || $tokens[$number]['code'] === T_DNUMBER) {
                $previous = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
                if ($previous !== false) {
                    $isAssignment = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$assignmentTokens);
                    $isEquality = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$equalityTokens);
                    $isComparison = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$comparisonTokens);
                    $isArithmetic = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$arithmeticTokens);
                    $isFunctionOrArray = in_array($tokens[$previous]['code'], array(T_OPEN_PARENTHESIS, T_OPEN_SQUARE_BRACKET));
                    $isFunctionArgument = ($tokens[$previous]['code'] === T_COMMA);
                    if ($isAssignment === true || $isEquality === true || $isComparison === true || $isArithmetic === true || $isFunctionOrArray === true || $isFunctionArgument === true) {
                        // This is a negative assignment, comparion, calculcation, function argument or array key usage.
                        // We need to check that the minus and the number are adjacent.
                        if (($number - $stackPtr) !== 1) {
                            $error = 'No space allowed between minus sign and number';
                            $phpcsFile->addError($error, $stackPtr, 'SpacingAfterMinus');
                        }
                        return;
                    }
                }
            }
        }

        if (($tokens[$stackPtr]['code'] === T_INC) || ($tokens[$stackPtr]['code'] === T_DEC)) {
            $prevStopToken = $phpcsFile->findPrevious(array(T_EQUAL, T_SEMICOLON), $stackPtr, null, false, null, true);
            $prev = $phpcsFile->findPrevious(T_VARIABLE, $stackPtr - 1, $prevStopToken, false, null, true);
            $next = $phpcsFile->findNext(T_VARIABLE, $stackPtr + 1, null, false, null, true);

            switch ($kindOfToken) {
                case 'postfix':
                    if ($this->existsWhitespace('before', $tokens, $stackPtr)) {
                        $error = 'No whitespace before the %s operator. Found "%s". Expected "%s"';
                        $code  = 'NoWhitSpaceBeforePostfix';
                        $found = rtrim($found);
                        $data  = array(
                                    $kindOfToken,
                                    $tokens[$prev]['content'] . $found,
                                    $tokens[$prev]['content'] . trim($found)
                                 );
                        $phpcsFile->addWarning($error, $stackPtr, $code, $data);
                    }
                    break;

                case 'prefix':
                    if ($this->existsWhitespace('after', $tokens, $stackPtr)) {
                        $error = 'No whitespace after the %s operator. Found "%s". Expected "%s"';
                        $code  = 'NoWhitSpaceAfterPrefix';
                        $found = ltrim($found);
                        $data  = array(
                                    $kindOfToken,
                                    $found . $tokens[$next]['content'],
                                    trim($found) . $tokens[$next]['content']
                                 );
                        $phpcsFile->addWarning($error, $stackPtr, $code, $data);
                    }
                    break;

                default:
            }

            return;
        }

        if ($this->existsWhitespace('before', $tokens, $stackPtr) === false && $this->existsWhitespace('after', $tokens, $stackPtr) === false) {
            $expected = $this->getExpectedString('before-after', $tokens, $stackPtr);
            $phpcsFile->addError('Whitespace must be added before and after the ' . $kindOfToken . ' operator. Found "' . $found . '". Expected "' . $expected . '"', $stackPtr);
        } elseif ($this->existsWhitespace('before', $tokens, $stackPtr) === false) {
            $expected = $this->getExpectedString('before', $tokens, $stackPtr);
            $phpcsFile->addError('Whitespace must be added before the ' . $kindOfToken . ' operator. Found "' . $found . '". Expected "' . $expected . '"', $stackPtr);
        } elseif ($this->existsWhitespace('after', $tokens, $stackPtr) === false) {
            $expected = $this->getExpectedString('after', $tokens, $stackPtr);
            $phpcsFile->addError('Whitespace must be added after the ' . $kindOfToken . ' operator. Found "' . $found . '". Expected "' . $expected . '"', $stackPtr);
        }
    }

    /**
     * Returns the kind of token.
     * Possible values: assignment, arithmetic, comparison
     *
     * @param array $token All tokens of the current file
     *
     * @return string
     */
    protected function getKindOfToken(array $token, PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $result = '';
        if (in_array($token['code'], PHP_CodeSniffer_Tokens::$assignmentTokens)) {
            $result = 'assignment';
        } elseif (in_array($token['code'], PHP_CodeSniffer_Tokens::$arithmeticTokens)) {
            $result = 'arithmetic';
        } elseif (in_array($token['code'], PHP_CodeSniffer_Tokens::$comparisonTokens)) {
            $result = 'comparison';
        } elseif (in_array($token['code'], $this->prePostFixTokens)) {
            $prevStopToken = $phpcsFile->findPrevious(array(T_EQUAL, T_SEMICOLON), $stackPtr, null, false, null, true);
            $prev = $phpcsFile->findPrevious(T_VARIABLE, $stackPtr - 1, $prevStopToken, false, null, true);
            $next = $phpcsFile->findNext(T_VARIABLE, $stackPtr + 1, null, false, null, true);

            if ($prev) {
                $result = 'postfix';
            } elseif ($next) {
                $result = 'prefix';
            }
        }

        return $result;
    }

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
        $found = $tokens[($stackPtr - 1) ]['content'] . $tokens[$stackPtr]['content'] . $tokens[($stackPtr + 1) ]['content'];
        return $found;
    }

    /**
     * Creates the expected string for the error message (correct formatting).
     *
     * @param string $mode     Depends on the mode, the expected string is different. Possible values: before, after, before-after
     * @param array  $tokens   Array of all tokens of the current file
     * @param int    $stackPtr Stack pointer of found token. Position in $tokens
     *
     * @return string
     */
    protected function getExpectedString($mode, array $tokens, $stackPtr)
    {
        $expected = $tokens[($stackPtr - 1) ]['content'];
        switch (strtolower($mode)) {
        case 'before':
            $expected.= ' ' . $tokens[$stackPtr]['content'];
            break;
        case 'after':
                $expected.= $tokens[$stackPtr]['content'] . ' ';
            break;
        case 'before-after':
            $expected.= ' ' . $tokens[$stackPtr]['content'] . ' ';
            break;
        }
        $expected.= $tokens[($stackPtr + 1) ]['content'];
        return $expected;
    }

    /**
     * Checks if there is whitespace before or after the incomming $stackPts
     *
     * @param string $mode     Mode which will be checked. Whitespace before or after. Possible values: before, after
     * @param array  $tokens   Array of all tokens of the current file
     * @param int    $stackPtr Current position in $tokens
     *
     * @return bool
     */
    protected function existsWhitespace($mode, array $tokens, $stackPtr)
    {
        $result = false;
        $stackPtr = $this->manageStackPtrCounter($mode, $stackPtr);
        if ($tokens[$stackPtr]['code'] == T_WHITESPACE) {
            $result = true;
        }
        return $result;
    }

    /**
     * Increments or decrements the incomming stack pointer (depends on $mode).
     *
     * @param string $mode     Mode to decide if +1 or -1 with $stackPtr. Possible values: before, after
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
    }
}
?>