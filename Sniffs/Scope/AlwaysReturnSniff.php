<?php
/**
 * TYPO3_Sniffs_Scope_AlwaysReturnSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Scope
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.de>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */

if (class_exists('PHP_CodeSniffer_CommentParser_FunctionCommentParser', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_CommentParser_FunctionCommentParser not found');
}

/**
 * Checks that a function / method always have a return value if it return something.
 *
 * @category  Scope
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.de>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Scope_AlwaysReturnSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports
     *
     * @var array
     */
    public $supportedTokenizes = array('PHP');

    /**
     * The function comment parser for the current method.
     *
     * @var PHP_CodeSniffer_Comment_Parser_FunctionCommentParser
     */
    protected $commentParser = null;

    /**
     * The current PHP_CodeSniffer_File object we are processing.
     *
     * @var PHP_CodeSniffer_File
     */
    protected $currentFile = null;

    /**
     * The position in the stack where the class token was found.
     *
     * @var int
     */
    protected $classToken = null;

    /**
     * The name of the method that we are currently processing.
     *
     * @var string
     */
    protected $methodName = '';


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);

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
        $this->currentFile = $phpcsFile;
        $tokens = $phpcsFile->getTokens();

        // Lets have a look if there is a doc comment. The doc comment could have a "@return void"
        // If there is a "@return void" there must not be a "return".
        $docComment = $this->getDocCommentOfFunction($phpcsFile, $stackPtr);

        $className = '';

        // Skip interfaces because the may have doc comments with @return annotations but no
        // function body with a real return statement.
        if ($this->classToken !== null) {
            if ($tokens[$this->classToken]['code'] == T_INTERFACE) {
                return;
            }
            $className = $this->currentFile->getDeclarationName($this->classToken);
            $className = strtolower(ltrim($className, '_'));
        }

        $start = $tokens[$stackPtr]['scope_opener'];
        $end   = $tokens[$stackPtr]['scope_closer'];

        // Skip constructor and destructor.
        $methodName      = strtolower(ltrim($this->methodName, '_'));
        $isSpecialMethod = ($this->methodName === '__construct' || $this->methodName === '__destruct');
        if ($isSpecialMethod === true || ($className !== '' && $methodName === $className)) {
            return;
        }

        if ($docComment !== null) {
            $returnContent = $this->getValueOfReturnTag();

            // If there is "@return void" defined in doc block comment
            // and there is a non empty return statement (e.g. return 5;)
            if (strtolower($returnContent) === 'void' && $this->checkAvailableReturnStatement($tokens, $start, $end) === true) {
                $error = 'This function must not have a return value because "@return void" is defined in doc comment.';
                $phpcsFile->addError($error, $stackPtr, 'ReturnStatementInVoidFunction');

                // If there is "@return int" or something like this defined in doc block comment
                // and there is a empty return statement
            } elseif ($returnContent !== null && strtolower($returnContent) !== 'void' && $this->checkAvailableReturnStatement($tokens, $start, $end, false) === true) {
                $error = 'This function must not have a empty return value because "@return %s" is defined in doc comment.';
                $errorData = array($returnContent);
                $phpcsFile->addError($error, $stackPtr, 'EmptyReturnStatementInNonVoidFunction', $errorData);
            }

            if (strtolower($returnContent) === 'void') {
                return;
            }
        }

        $result = false;
        do {
            $next = $phpcsFile->findNext(array(T_RETURN), $start, $end);
            if ($next !== false && $this->isReturnSurroundedByControllStructures($tokens, $next) === false) {
                $result = true;
                $next   = false;
            } else {
                $start = ($next + 1);
            }
        } while ($next !== false);

        if ($result === false) {
            $error = 'This function must always have a return value.';
            $phpcsFile->addError($error, $stackPtr, 'AlwaysReturnStatement');
        }

    }

    /**
     * This methods checks for return statements.
     *
     * If there is a doc comment like "@return void".
     * A forbidden return statement is in this context all return statement expect "return;".
     * Like "return $foo;", "return 5;", "return null;", ...
     *
     * If there is a doc comment like "@return int", "@return bool", ...
     * A forbidden return statement is in this context "return;"
     * Because in a method with defined @return statement there must not be empty return statements.
     *
     * @param array   $tokens     Token array of file
     * @param integer $tokenStart Integer, token number where the checks will begin
     * @param integer $tokenEnd   Integer, token number where the checks will end
     * @param bool    $nonEmpty   If true, function returns true if there is a non empty return statement like "return $foo;"
     *                            If false, function returns true if there is a empty return statement like "return;"
     *
     * @return bool
     */
    protected function checkAvailableReturnStatement(array $tokens, $tokenStart, $tokenEnd, $nonEmpty = true)
    {
        $returnStatementResult = false;

        do {
            $returnResult = null;
            $result = $this->currentFile->findNext(array(T_RETURN), $tokenStart, $tokenEnd);

                // If there is a return statement in this function / method, try to find the next token, expect whitespaces
            if ($result !== false) {
                $returnResult = $this->currentFile->findNext(array(T_WHITESPACE), $result + 1, $tokenEnd, true, null, true);
            }

                // If there is no return-Statement between $tokenStart and $tokenEnd, stop here with the loop
            if ($result === false) {
                $tokenStart = $tokenEnd;

                // If there is a return-Statement between $tokenStart and $tokenEnd, check if the next relevant
                // token is a T_SEMICOLON. If no, this is a normal return statement like "return $foo;".
            } elseif ($nonEmpty === true && $result !== false && $returnResult !== false && $tokens[$returnResult]['code'] !== T_SEMICOLON) {
                $returnStatementResult = true;
                break;

                // If there is a return-Statement between $tokenStart and $tokenEnd, check if the next relevant
                // token is a T_SEMICOLON. If yes, this is a empty return statement like "return;".
            } elseif ($nonEmpty === false && $result !== false && $returnResult !== false && $tokens[$returnResult]['code'] === T_SEMICOLON) {
                $returnStatementResult = true;
                break;

                // If no case is affected, raise the pointer :)
            } else {
                $tokenStart = $result + 1;
            }
        } while ($tokenStart < $tokenEnd);

        return $returnStatementResult;
    }

    /**
     * Checks if the return statement is surrounded by control structures.
     *
     * @param array $tokens         All tokens of this file
     * @param int   $stackPtr       Stack pointer for found token
     * @param int   &$functionToken Reference, function token will be saved
     *
     * @return boolean
     */
    protected function isReturnSurroundedByControllStructures(array $tokens, $stackPtr, &$functionToken = 0)
    {
        $result = false;
        foreach ($tokens[$stackPtr]['conditions'] as $key => $val) {
            if ($tokens[$key]['code'] == T_FUNCTION) {
                $functionToken = $key;
            }
            if ($tokens[$key]['code'] !== T_CLASS && $tokens[$key]['code'] !== T_FUNCTION) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Ths function is mainly copied from PEAR_Sniffs_Commenting_FunctionCommentSniff.
     * THX for this!
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return mixed
     */
    protected function getDocCommentOfFunction(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $find = array(
            T_COMMENT,
            T_DOC_COMMENT,
            T_CLASS,
            T_FUNCTION,
            T_OPEN_TAG,
        );

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1));

        if ($commentEnd === false) {
            return null;
        }

        $tokens = $phpcsFile->getTokens();

            // If the token that we found was a class or a function, then this
            // function has no doc comment.
        $code = $tokens[$commentEnd]['code'];

            // If the comment a "//" comment, get out of here
        if ($code === T_COMMENT) {
            return null;

            // If there is no doc comment block, get out of here, too
        } else if ($code !== T_DOC_COMMENT) {
            return null;
        }

            // If there is any code between the function keyword and the doc block
            // then the doc block is not for us.
        $ignore    = PHP_CodeSniffer_Tokens::$scopeModifiers;
        $ignore[]  = T_STATIC;
        $ignore[]  = T_WHITESPACE;
        $ignore[]  = T_ABSTRACT;
        $ignore[]  = T_FINAL;
        $prevToken = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), null, true);
        if ($prevToken !== $commentEnd) {
            return null;
        }

        $this->classToken = null;
        foreach ($tokens[$stackPtr]['conditions'] as $condPtr => $condition) {
            if ($condition === T_CLASS || $condition === T_INTERFACE) {
                $this->classToken = $condPtr;
                break;
            }
        }

            // If the first T_OPEN_TAG is right before the comment, it is probably
            // a file comment.
        $commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
        $prevToken    = $phpcsFile->findPrevious(T_WHITESPACE, ($commentStart - 1), null, true);
        if ($tokens[$prevToken]['code'] === T_OPEN_TAG) {
            // Is this the first open tag?
            if ($stackPtr === 0 || $phpcsFile->findPrevious(T_OPEN_TAG, ($prevToken - 1)) === false) {
                return null;
            }
        }

        $comment           = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));
        $this->methodName  = $phpcsFile->getDeclarationName($stackPtr);

        try {
            $this->commentParser = new PHP_CodeSniffer_CommentParser_FunctionCommentParser($comment, $phpcsFile);
            $this->commentParser->parse();
        } catch (PHP_CodeSniffer_CommentParser_ParserException $e) {
            return null;
        }

        $comment = $this->commentParser->getComment();
        if (is_null($comment) === true) {
            return null;
        }

        return $this->commentParser;
    }

    /**
     * Process the return comment of this function comment.
     *
     * @return void
     */
    protected function getValueOfReturnTag()
    {
        $returnContent = $tmpContent = null;
        $pairElement = $this->commentParser->getReturn();
        if ($pairElement instanceof PHP_CodeSniffer_CommentParser_AbstractDocElement) {
            $tmpContent = trim($this->commentParser->getReturn()->getRawContent());
        }

        if ($tmpContent !== null) {
            $returnContent = $tmpContent;
        }

        return $returnContent;
    }
}
?>