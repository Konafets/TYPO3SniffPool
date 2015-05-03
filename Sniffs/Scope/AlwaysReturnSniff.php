<?php
/**
 * TYPO3_Sniffs_Scope_AlwaysReturnSniff.
 *
 * PHP version 5
 * TYPO3 CMS
 *
 * @category  Scope
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.de>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Andy Grunwald
 * @copyright 2014 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

/**
 * Checks that a function / method always have a return value if it return something.
 *
 * @category  Scope
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.de>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Andy Grunwald
 * @copyright 2014 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_Scope_AlwaysReturnSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP');

    /**
     * The current PHP_CodeSniffer_File object we are processing.
     *
     * @var PHP_CodeSniffer_File
     */
    protected $currentFile = null;

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
        $tokens            = $phpcsFile->getTokens();
        $this->currentFile = $phpcsFile;

        foreach ($tokens[$stackPtr]['conditions'] as $condPtr => $condition) {
            // Skip interfaces because the may have doc comments with @return
            // annotations but no function body with a real return statement.
            if ($condition === T_INTERFACE) {
                return;
            }
        }

        // Skip constructor and destructor.
        $methodName      = $phpcsFile->getDeclarationName($stackPtr);
        $isSpecialMethod = ($methodName === '__construct' || $methodName === '__destruct');

        if ($isSpecialMethod === true) {
            return;
        }

        // Skip files without scope openers
        // This will skip also abstract functions, which is intended.
        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            return;
        }

        $find   = PHP_CodeSniffer_Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;

        // Lets have a look if there is a doc comment.
        // The doc comment could have a "@return void"
        // If there is a "@return void" there must not be a "return".
        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);
        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            // Inline comments might just be closing comments for
            // control structures or functions instead of function comments
            // using the wrong comment type. If there is other code on the line,
            // assume they relate to that code.
            $prev = $phpcsFile->findPrevious($find, ($commentEnd - 1), null, true);
            if ($prev !== false && $tokens[$prev]['line'] === $tokens[$commentEnd]['line']) {
                $commentEnd = $prev;
            }
        }

        $return = null;
        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
            && $tokens[$commentEnd]['code'] !== T_COMMENT
        ) {
            $return = null;
        } else {
            $commentStart = $tokens[$commentEnd]['comment_opener'];

            foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
                if ($tokens[$tag]['content'] === '@return') {
                    $return = $tag;
                }
            }
        }

        $start = $tokens[$stackPtr]['scope_opener'];
        $end   = $tokens[$stackPtr]['scope_closer'];

        if ($return !== null) {
            $tagValue      = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $return);
            $returnContent = $tokens[$tagValue]['content'];

            // If there is "@return void" defined in doc block comment
            // and there is a non empty return statement (e.g. return 5;).
            if (strtolower($returnContent) === 'void' && $this->checkAvailableReturnStatement($tokens, $start, $end) === true) {
                $error = 'The function "%s" must not have a return value because "@return void" is defined in doc comment.';
                $phpcsFile->addError(
                    $error,
                    $stackPtr,
                    'ReturnStatementInVoidFunction',
                    array($methodName)
                );

                // If there is "@return int" or something like this defined in
                // doc block comment and there is a empty return statement.
            } else if ($returnContent !== null
                && strtolower($returnContent) !== 'void'
                && $this->checkAvailableReturnStatement($tokens, $start, $end, false) === true
            ) {
                $error     = 'Found an empty return statement "return;" inside the function "%s", but also a "@return %s" in doc comment. Change the doc declaration to "@return void" or adjust the return statement. ';
                $errorData = array(
                              $methodName,
                              $returnContent,
                             );
                $phpcsFile->addError($error, $stackPtr, 'EmptyReturnStatementInNonVoidFunction', $errorData);
            }//end if

            if (strtolower($returnContent) === 'void') {
                return;
            }
        }//end if

        // Find last return statement.
        $lastReturnStatement = $phpcsFile->findPrevious(T_RETURN, $end);

        // Check if the last return statement got only "natural" conditions, like classes or functions.
        $gotClassCondition    = $phpcsFile->hasCondition($lastReturnStatement, T_CLASS);
        $gotFunctionCondition = $phpcsFile->hasCondition($lastReturnStatement, T_FUNCTION);
        if ((count($tokens[$lastReturnStatement]['conditions']) === 1 && $gotFunctionCondition === true) || (count($tokens[$lastReturnStatement]['conditions']) === 2 && $gotClassCondition === true && $gotFunctionCondition === true)
        ) {
            return;
        }

        // Here begins the "dirty" part
        // Now we have to check if there is a return statement in control structures
        // Why dirty? Because we have to check if there is a return statement
        // in EVERY control structure to fit the rule that there is ALWAYS a
        // return statement.
        $result = $this->gotEveryWayOfControlStructureAReturnStatement($phpcsFile, $start, $end, 0);

        if ($result === false) {
            $error = 'This function must always have a return value.';
            $phpcsFile->addError($error, $stackPtr, 'AlwaysReturnStatement');
        }

    }//end process()


    /**
     * This method is the last fallback of this sniff.
     * If the function / method got no return statement outsie of a control
     * structure, this one will check if every possible way got a return statement.
     * If yes, it returns true.
     * Otherwise no.
     *
     * Attention. This function is called recursively.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned
     * @param integer              $start     Token number where the checks will
     *                                        begin
     * @param integer              $end       Token number where the checks will end
     * @param integer              $depth     Token to search
     *
     * @return bool
     */
    protected function gotEveryWayOfControlStructureAReturnStatement(PHP_CodeSniffer_File $phpcsFile, $start, $end, $depth)
    {
        $result = true;
        $tokens = $phpcsFile->getTokens();
        $depth++;

        $returnStatement = $phpcsFile->findNext(T_RETURN, $start, $end);

        // If there is no return statement in range $start and $end, exit here.
        if ($returnStatement === false) {
            return false;
        }

        // Determine condition of recursive depth / level
        // Conditions can contain classes and function, but we are only
        // interested in if, elseif, else.
        $i = 0;
        foreach ($tokens[$returnStatement]['conditions'] as $tokenNumber => $conditionType) {
            $startToken = $tokenNumber;
            if ($i === $depth) {
                break;
            }

            $i++;
        }

        // If we found our first return statement in an else or elseif statement,
        // we must determine the if statement before.
        if ($tokens[$startToken]['code'] === T_ELSEIF || $tokens[$startToken]['code'] === T_ELSE) {
            $level      = $tokens[$startToken]['level'];
            $startToken = $this->getPreviousTokenOnLevel($phpcsFile, $startToken, $start, T_IF, $level);
        }

        // $controlStructureTokens = array(T_IF, T_ELSEIF, T_ELSE);
        $onlyIfStatement = true;

        // Lets have a look if there is a return statement in the control
        // structure on the same level. This is only allowed if the depth is > 1,
        // because 1 means a control structure on the first levelof a function, like
        //
        // function foo() {
        // if (bar) {
        // return 1;
        // }
        // }
        //
        // and have a look if there is a else statement in the nested control
        // structure like
        //
        // function foo() {
        // if (bar) {
        // if (baz) {
        // return 1;
        // } else {
        // return 2;
        // }
        // }
        // }
        // Thats all.
        $isOnlyIfStatementAllowed = false;
        if ($depth > 1) {
            // We have to increment the level, because the return statement is one level deeper as the control structure.
            $returnOnSameLevel         = (bool) $this->getPreviousTokenOnLevel($phpcsFile, $tokens[$startToken]['scope_closer'], $tokens[$startToken]['scope_opener'], T_RETURN, ($tokens[$startToken]['level'] + 1));
            $elseWithReturnOnNextLevel = (bool) $this->isThereAElseWithReturnOnNextLevel($phpcsFile, $tokens[$startToken]['scope_opener'], $tokens[$startToken]['scope_closer'], $tokens[$startToken]['level']);
            $isOnlyIfStatementAllowed  = $returnOnSameLevel || $elseWithReturnOnNextLevel;
        }

        do {
            $scopeOpener = $tokens[$startToken]['scope_opener'];
            $scopeCloser = $tokens[$startToken]['scope_closer'];

            $returnStatement = $phpcsFile->findNext(T_RETURN, $scopeOpener, $scopeCloser);
            if ($returnStatement === false) {
                $result = false;
            }

            // Check if the found return statement is (minimum) one level deeper
            // (e.g. nested control structures)If is this the case, we call this
            // method again.
            $levelOfControlStructure = $tokens[$scopeOpener]['level'];
            $levelOfReturn           = $tokens[$returnStatement]['level'];

            if (($levelOfReturn - 1) > $levelOfControlStructure) {
                // Determine next level of control structure
                // We have to to this, because we can find a really nested deep
                // return statement but we have to get only one level deeper.
                $searchedLevel = ($levelOfControlStructure + 1);
                $nestedToken   = $this->getPreviousTokenOnLevel($phpcsFile, $returnStatement, $scopeOpener, T_IF, $searchedLevel);

                if (isset($tokens[$nestedToken]['scope_opener']) === false) {
                    return false;
                }

                $recursionStart = $tokens[$nestedToken]['scope_opener'];
                $recursionEnd   = $tokens[$nestedToken]['scope_closer'];
                $result         = $this->gotEveryWayOfControlStructureAReturnStatement($phpcsFile, $recursionStart, $recursionEnd, $depth);
            }

            // Get next elseif or else
            // If we found one we have to check them as well.
            $nextToken = $phpcsFile->findNext(T_WHITESPACE, ($scopeCloser + 1), null, true);
            if ($nextToken !== false && ($tokens[$nextToken]['code'] === T_ELSEIF || $tokens[$nextToken]['code'] === T_ELSE)) {
                $startToken = $nextToken;

                // Switch that there is not only one if statement
                // If there is only one if statement, this is an error as well,
                // because then we got no return statement if the if does not match.
                $onlyIfStatement = false;
            } else {
                $nextToken = false;
            }
        } while ($result === true && $nextToken !== false);

        if ($isOnlyIfStatementAllowed === false && $onlyIfStatement === true) {
            $result = !$onlyIfStatement;
        }

        return $result;

    }//end gotEveryWayOfControlStructureAReturnStatement()


    /**
     * Looks for the previous IF-Statement on a specified level.
     * If you got a token of an elseif or else statement, this method will
     * return the number of the corresponding if token.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned
     * @param integer              $start     Token number where the checks will
     *                                        begin
     * @param integer              $end       Token number where the checks will
     *                                        end
     * @param integer              $token     Token to search
     * @param integer              $level     Level of intend (how deep is the
     *                                        token?)
     *
     * @return integer                        Token number of found token
     */
    protected function getPreviousTokenOnLevel(PHP_CodeSniffer_File $phpcsFile, $start, $end, $token, $level)
    {
        $tokens = $phpcsFile->getTokens();
        do {
            $searchedToken = $phpcsFile->findPrevious($token, $start, $end);
            $start         = ($searchedToken - 1);
        } while ($searchedToken !== false && $tokens[$searchedToken]['level'] !== $level);

        return $searchedToken;

    }//end getPreviousTokenOnLevel()


    /**
     * Will check if in $start + $end is a else-part with including return statement.
     * Returns true if found. False otherwise.
     *
     * Example:
     *  function foo() {
     *      if (bar) {          << Start (the "{")
     *          if (baz) {
     *              return 1;
     *          } else {
     *              return 2;   << Will be searched (the "return")
     *          }
     *      }                   << End (the "}")
     *  }
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned
     * @param integer              $start     Token number where the checks will
     *                                        begin
     * @param integer              $end       Token number where the checks will
     *                                        end
     * @param integer              $level     Level of intend (how deep is the
     *                                        token?)
     *
     * @return bool
     */
    protected function isThereAElseWithReturnOnNextLevel(PHP_CodeSniffer_File $phpcsFile, $start, $end, $level)
    {
        $tokens    = $phpcsFile->getTokens();
        $elseToken = $this->getPreviousTokenOnLevel($phpcsFile, $end, $start, T_ELSE, ($level + 1));

        if ($elseToken === false) {
            return false;
        }

        $returnToken = $this->getPreviousTokenOnLevel(
            $phpcsFile,
            $tokens[$elseToken]['scope_closer'],
            $tokens[$elseToken]['scope_opener'],
            T_RETURN,
            ($level + 2)
        );

        return (bool) $returnToken;

    }//end isThereAElseWithReturnOnNextLevel()


    /**
     * This methods checks for return statements.
     *
     * If there is a doc comment like "@return void".
     * A forbidden return statement is in this context all return statement
     * expect "return;".
     * Like "return $foo;", "return 5;", "return null;", ...
     *
     * If there is a doc comment like "@return int", "@return bool", ...
     * A forbidden return statement is in this context "return;"
     * Because in a method with defined @return statement there must not be empty
     * return statements.
     *
     * @param array   $tokens     Token array of file
     * @param integer $tokenStart Integer, token number where the checks will begin
     * @param integer $tokenEnd   Integer, token number where the checks will end
     * @param bool    $nonEmpty   If true, function returns true if there is a
     *                            non empty return statement like "return $foo;"
     *                            If false, function returns true if there is
     *                            a empty return statement like "return;"
     *
     * @return bool
     */
    protected function checkAvailableReturnStatement(array $tokens, $tokenStart, $tokenEnd, $nonEmpty = true)
    {
        $returnStatementResult = false;
        do {
            $returnResult = null;
            $result       = $this->currentFile->findNext(array(T_RETURN), $tokenStart, $tokenEnd);

            // If there is a return statement in this function / method, try to
            // find the next token, expect whitespaces.
            if ($result !== false) {
                $returnResult = $this->currentFile->findNext(array(T_WHITESPACE), ($result + 1), $tokenEnd, true, null, true);
            }

            // If there is no return-Statement between $tokenStart and
            // $tokenEnd, stop here with the loop.
            if ($result === false) {
                $tokenStart = $tokenEnd;

                // If there is a return-Statement between $tokenStart and $tokenEnd,
                // check if the this return statement is part of an anonymous
                // functions. In this case, this will be ignored.
            } else if ($nonEmpty === true && $this->currentFile->hasCondition($result, T_CLOSURE) === true) {
                break;

                // If there is a return-Statement between $tokenStart and $tokenEnd,
                // check if the next relevant token is a T_SEMICOLON. If no, this
                // is a normal return statement like "return $foo;".
            } else if ($nonEmpty === true && $result !== false && $returnResult !== false && $tokens[$returnResult]['code'] !== T_SEMICOLON) {
                $returnStatementResult = true;
                break;

                // If there is a return-Statement between $tokenStart and $tokenEnd,
                // check if the next relevant token is a T_SEMICOLON. If yes, this
                // is a empty return statement like "return;".
            } else if ($nonEmpty === false && $result !== false && $returnResult !== false && $tokens[$returnResult]['code'] === T_SEMICOLON) {
                $returnStatementResult = true;
                break;

                // If no case is affected, raise the pointer :).
            } else {
                $tokenStart = ($result + 1);
            }//end if
        } while ($tokenStart < $tokenEnd);

        return $returnStatementResult;

    }//end checkAvailableReturnStatement()


    /**
     * Checks if the return statement is surrounded by control structures.
     *
     * @param array $tokens        All tokens of this file
     * @param int   $stackPtr      Stack pointer for found token
     * @param int   $functionToken Reference, function token will be saved
     *
     * @return boolean
     */
    protected function isReturnSurroundedByControlStructures(array $tokens, $stackPtr, &$functionToken = 0)
    {
        $result = false;
        foreach ($tokens[$stackPtr]['conditions'] as $key => $val) {
            if ($tokens[$key]['code'] === T_FUNCTION) {
                $functionToken = $key;
            }

            if ($tokens[$key]['code'] !== T_CLASS && $tokens[$key]['code'] !== T_FUNCTION) {
                $result = true;
            }
        }

        return $result;

    }//end isReturnSurroundedByControlStructures()


}//end class
