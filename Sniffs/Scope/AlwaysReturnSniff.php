<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2010 Andy Grunwald <andygrunwald@gmail.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * TYPO3_Sniffs_Scope_AlwaysReturnSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Scope
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.de>
 * @copyright Copyright (c) 2010, Andy Grunwald
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
 * @copyright Copyright (c) 2010, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Sniffs_Scope_AlwaysReturnSniff implements PHP_CodeSniffer_Sniff
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
        $this->currentFile = $phpcsFile;
        $tokens = $phpcsFile->getTokens();

        // Lets have a look if there is a doc comment. The doc comment could have a "@return void"
        // If there is a "@return void" there must not be a "return".
        $docComment = $this->getDocCommentOfFunction($phpcsFile, $stackPtr);

        $className = '';

        // Skip interfaces because the may have doc comments with @return annotations but no
        // function body with a real return statement.
        if ($this->classToken !== null) {
            if ($tokens[$this->classToken]['code'] == T_INTERFACE)
            {
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

            if (strtolower($returnContent) === 'void' && $phpcsFile->findNext(array(T_RETURN), $start, $end) !== false) {
                $error = 'This function must not be a return value, because "@return void" is defined in doc comment';
                $phpcsFile->addError($error, $stackPtr, 'ReturnStatementInVoidFunction');
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
            $error = 'This function must always have a return value';
            $phpcsFile->addError($error, $stackPtr, 'AlwaysReturnStatement');
        }

    }//end process()


    /**
     * Checks if the return statement is surrounded by control structures.
     *
     * @param array $tokens
     * @param int   $stackPtr
     * @param int   $functionToken
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

    }//end isReturnSurroundedByControllStructures()


    /**
     * Ths function is mainly copied from PEAR_Sniffs_Commenting_FunctionCommentSniff.
     * THX for this!
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param $stackPtr
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

    }//end getDocCommentOfFunction()


    /**
     * Process the return comment of this function comment.
     *
     * @return void
     */
    protected function getValueOfReturnTag()
    {
        $returnContent = $tmpContent = null;
        $pairElement = $this->commentParser->getReturn();
        if($pairElement instanceof PHP_CodeSniffer_CommentParser_AbstractDocElement) {
            $tmpContent = trim($this->commentParser->getReturn()->getRawContent());
        }

        if ($tmpContent !== null) {
            $returnContent = $tmpContent;
        }

        return $returnContent;
    }//end getValueOfReturnTag()
}
?>