<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2010 Andy Grunwald <andreas.grunwald@wmdb.de>
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
 * TYPO3_Sniffs_Commenting_FunctionDocCommentSniff
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category	Commenting
 * @package		TYPO3_PHPCS_Pool
 * @author		Greg Sherwood <gsherwood@squiz.net>
 * @author		Marc McIntyre <mmcintyre@squiz.net>
 * @author		Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright	2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license		http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version     SVN: $ID$
 * @link		http://pear.typo3.org
 */
if (class_exists('PHP_CodeSniffer_CommentParser_FunctionCommentParser', TRUE) === FALSE) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_CommentParser_FunctionCommentParser not found');
}
/**
 * Parses and verifies the doc comments for functions / methods.
 *
 * This sniff was copied and modified
 * from PEAR_Sniffs_Commenting_FunctionCommentSniff.
 * Thanks for this guys!
 *
 * Verifies that :
 * <ul>
 *  <li>A doc comment exists</li>
 *  <li>A doc comment is made by "/**"-Comments.</li>
 *  <li>A doc comment is not empty.</li>
 *  <li>There is no blank newline before the description.</li>
 *  <li>There is a blank newline between the description and tags.</li>
 *  <li>Parameter names represent those in the method.</li>
 *  <li>Parameter comments are in the correct order</li>
 *  <li>Parameter comments are complete</li>
 *  <li>Parameter comments are correct aligned via tabs</li>
 *  <li>A return type exists</li>
 *  <li>Any throw tag must have an exception class.</li>
 * </ul>
 *
 * @category	Commenting
 * @package		TYPO3_PHPCS_Pool
 * @author		Greg Sherwood <gsherwood@squiz.net>
 * @author		Marc McIntyre <mmcintyre@squiz.net>
 * @author		Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright	2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license		http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version     Release: @package_version@
 * @link		http://pear.typo3.org
 */
class TYPO3_Sniffs_Commenting_FunctionDocCommentSniff implements PHP_CodeSniffer_Sniff {
    /**
     * The name of the method that we are currently processing.
     *
     * @var string
     */
    private $_methodName = '';
    /**
     * The position in the stack where the fucntion token was found.
     *
     * @var int
     */
    private $_functionToken = null;
    /**
     * The position in the stack where the class token was found.
     *
     * @var int
     */
    private $_classToken = null;
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
     * Returns an array of tokens this test wants to listen for.
     *
     * foo foof oo
     *
     * ssss
     *
     * @return array
     */
    public function register() {
        return array(T_FUNCTION);
    }
    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $find = array(T_COMMENT, T_DOC_COMMENT, T_CLASS, T_FUNCTION, T_OPEN_TAG,);
        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        if ($commentEnd === FALSE) {
            return;
        }
        $this->currentFile = $phpcsFile;
        $tokens = $phpcsFile->getTokens();
        // If the token that we found was a class or a function, then this
        // function has no doc comment.
        $code = $tokens[$commentEnd]['code'];
        if ($code === T_COMMENT) {
            $error = 'You must use "/**" style comments for a function comment';
            $phpcsFile->addError($error, $stackPtr, 'WrongStyle');
            return;
        } elseif ($code !== T_DOC_COMMENT) {
            $phpcsFile->addError('Missing function doc comment', $stackPtr, 'Missing');
            return;
        }
        // If there is any code between the function keyword and the doc block
        // then the doc block is not for us.
        $ignore = PHP_CodeSniffer_Tokens::$scopeModifiers;
        $ignore[] = T_STATIC;
        $ignore[] = T_WHITESPACE;
        $ignore[] = T_ABSTRACT;
        $ignore[] = T_FINAL;
        $prevToken = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), NULL, TRUE);
        if ($prevToken !== $commentEnd) {
            $phpcsFile->addError('Missing function doc comment', $stackPtr, 'Missing');
            return;
        }
        $this->_functionToken = $stackPtr;
        $this->_classToken = NULL;
        foreach ($tokens[$stackPtr]['conditions'] as $condPtr => $condition) {
            if ($condition === T_CLASS || $condition === T_INTERFACE) {
                $this->_classToken = $condPtr;
                break;
            }
        }
        // If the first T_OPEN_TAG is right before the comment, it is probably
        // a file comment.
        $commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), NULL, TRUE) + 1);
        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($commentStart - 1), NULL, TRUE);
        if ($tokens[$prevToken]['code'] === T_OPEN_TAG) {
            // Is this the first open tag?
            if ($stackPtr === 0 || $phpcsFile->findPrevious(T_OPEN_TAG, ($prevToken - 1)) === FALSE) {
                $phpcsFile->addError('Missing function doc comment', $stackPtr, 'Missing');
                return;
            }
        }
        $comment = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));
        $this->_methodName = $phpcsFile->getDeclarationName($stackPtr);
        try {
            $this->commentParser = new PHP_CodeSniffer_CommentParser_FunctionCommentParser($comment, $phpcsFile);
            $this->commentParser->parse();
        }
        catch(PHP_CodeSniffer_CommentParser_ParserException $e) {
            $line = ($e->getLineWithinComment() + $commentStart);
            $phpcsFile->addError($e->getMessage(), $line, 'FailedParse');
            return;
        }
        $comment = $this->commentParser->getComment();
        if (is_null($comment) === TRUE) {
            $error = 'Function doc comment is empty';
            $phpcsFile->addError($error, $commentStart, 'Empty');
            return;
        }
        $this->processParams($commentStart);
        $this->processReturn($commentStart, $commentEnd);
        $this->processThrows($commentStart);
        // No extra newline before short description.
        $short = $comment->getShortComment();
        $newlineCount = 0;
        $newlineSpan = strspn($short, $phpcsFile->eolChar);
        if ($short !== '' && $newlineSpan > 0) {
            $error = 'Extra newline(s) found before function comment short description';
            $phpcsFile->addError($error, ($commentStart + 1), 'SpacingBeforeShort');
        }
        $newlineCount = (substr_count($short, $phpcsFile->eolChar) + 1);
        // Exactly one blank line before tags.
        $params = $this->commentParser->getTagOrders();
        if (count($params) > 1) {
            $newlineSpan = $comment->getNewlineAfter();
            if ($newlineSpan !== 2) {
                $error = 'There must be exactly one blank line before the tags in function comment';
                $long = $comment->getLongComment();
                if ($long !== '') {
                    $newlineCount+= (substr_count($long, $phpcsFile->eolChar) - $newlineSpan + 1);
                }
                $phpcsFile->addError($error, ($commentStart + $newlineCount), 'SpacingBeforeTags');
                $short = rtrim($short, $phpcsFile->eolChar . ' ');
            }
        }
        return NULL;
    }
    /**
     * Process any throw tags that this function comment has.
     *
     * @param int $commentStart The position in the stack where the
     *                          comment started.
     *
     * @return void
     */
    protected function processThrows($commentStart) {
        if (count($this->commentParser->getThrows()) === 0) {
            return;
        }
        foreach ($this->commentParser->getThrows() as $throw) {
            $exception = $throw->getValue();
            $errorPos = ($commentStart + $throw->getLine());
            if ($exception === '') {
                $error = '@throws tag must contain the exception class name';
                $this->currentFile->addError($error, $errorPos, 'EmptyThrows');
            }
        }
    }
    /**
     * Process the return comment of this function comment.
     *
     * @param int $commentStart The position in the stack where the comment started.
     * @param int $commentEnd   The position in the stack where the comment ended.
     *
     * @return void
     */
    protected function processReturn($commentStart, $commentEnd) {
        // Skip constructor and destructor.
        $className = '';
        if ($this->_classToken !== NULL) {
            $className = $this->currentFile->getDeclarationName($this->_classToken);
            $className = strtolower(ltrim($className, '_'));
        }
        $methodName = strtolower(ltrim($this->_methodName, '_'));
        $isSpecialMethod = ($this->_methodName === '__construct' || $this->_methodName === '__destruct');
        if ($isSpecialMethod === FALSE && $methodName !== $className) {
            // Report missing return tag.
            if ($this->commentParser->getReturn() === NULL) {
                $error = 'Missing @return tag in function comment';
                $this->currentFile->addError($error, $commentEnd, 'MissingReturn');
            } elseif (trim($this->commentParser->getReturn()->getRawContent()) === '') {
                $error = '@return tag is empty in function comment';
                $errorPos = ($commentStart + $this->commentParser->getReturn()->getLine());
                $this->currentFile->addError($error, $errorPos, 'EmptyReturn');
            }
        }
    }
    /**
     * Process the function parameter comments.
     *
     * @param int $commentStart The position in the stack where
     *                          the comment started.
     *
     * @return void
     */
    protected function processParams($commentStart) {
        $realParams = $this->currentFile->getMethodParameters($this->_functionToken);
        $params = $this->commentParser->getParams();
        $foundParams = array();
        if (empty($params) === FALSE) {
            $lastParm = (count($params) - 1);
            if (substr_count($params[$lastParm]->getWhitespaceAfter(), $this->currentFile->eolChar) !== 1) {
                $error = 'Last parameter comment must not a blank newline after it';
                $errorPos = ($params[$lastParm]->getLine() + $commentStart);
                $this->currentFile->addError($error, $errorPos, 'SpacingAfterParams');
            }
            // Parameters must appear immediately after the comment.
            if ($params[0]->getOrder() !== 2) {
                $error = 'Parameters must appear immediately after the comment';
                $errorPos = ($params[0]->getLine() + $commentStart);
                $this->currentFile->addError($error, $errorPos, 'SpacingBeforeParams');
            }
            $previousParam = NULL;
            foreach ($params as $param) {
                $paramComment = trim($param->getComment());
                $errorPos = ($param->getLine() + $commentStart);
                
                // Make sure they are in the correct order,
                // and have the correct name.
                $pos = $param->getPosition();
                $paramName = ($param->getVarName() !== '') ? $param->getVarName() : '[ UNKNOWN ]';
                // Make sure the names of the parameter comment matches the
                // actual parameter.
                if (isset($realParams[($pos - 1) ]) === TRUE) {
                    // Make sure that there are only tabs used to intend the var type.
                    if ($this->isWhitespaceUsedToIntend($param->getWhitespaceBeforeType())) {
                        $error = 'Tabs must be used to indent the variable type; spaces are not allowed';
                        $this->currentFile->addError($error, $errorPos, 'SpacingBeforeParamType');
                    }
                    // Make sure that there are only tabs used to intend the var comment.
                    if ($this->isWhitespaceUsedToIntend($param->getWhiteSpaceBeforeComment())) {
                        $error = 'Tabs must be used to indent the variable comment; spaces are not allowed';
                        $this->currentFile->addError($error, $errorPos, 'SpacingBeforeParamComment');
                    }
                    // Make sure that there are only tabs used to intend the var name.
                    if ($param->getVarName() && $this->isWhitespaceUsedToIntend($param->getWhiteSpaceBeforeVarName())) {
                        $error = 'Tabs must be used to indent the variable name; spaces are not allowed';
                        $this->currentFile->addError($error, $errorPos, 'SpacingBeforeParamName');
                    }

                    $realName = $realParams[($pos - 1) ]['name'];
                    $foundParams[] = $realName;
                    // Append ampersand to name if passing by reference.
                    if ($realParams[($pos - 1) ]['pass_by_reference'] === TRUE) {
                        $realName = '&' . $realName;
                    }
                    if ($realName !== $paramName) {
                        $code = 'ParamNameNoMatch';
                        $data = array($paramName, $realName, $pos,);
                        $error = 'Doc comment for var %s does not match ';
                        if (strtolower($paramName) === strtolower($realName)) {
                            $error.= 'case of ';
                            $code = 'ParamNameNoCaseMatch';
                        }
                        $error.= 'actual variable name %s at position %s';
                        $this->currentFile->addError($error, $errorPos, $code, $data);
                    }
                } else {
                    // Throw an error if we found a parameter in comment but not in the parameter list of the function
                    $error = 'The paramter "' . $paramName . '" at position ' . $pos . ' is superfluous, because this parameter was not found in parameter list.';
                    $this->currentFile->addError($error, $errorPos, 'SuperFluous.ParamComment');
                }
                if ($param->getVarName() === '') {
                    $error = 'Missing parameter name at position ' . $pos;
                    $this->currentFile->addError($error, $errorPos, 'MissingParamName');
                }
                if ($param->getType() === '') {
                    $error = 'Missing type at position ' . $pos;
                    $this->currentFile->addError($error, $errorPos, 'MissingParamType');
                }
//                if ($paramComment === '') {
//                    $error = 'Missing comment for param "%s" at position %s';
//                    $data = array($paramName, $pos,);
//                    $this->currentFile->addError($error, $errorPos, 'MissingParamComment', $data);
//                }
                $previousParam = $param;
            }
        }
        $realNames = array();
        foreach ($realParams as $realParam) {
            $realNames[] = $realParam['name'];
        }
        // Report and missing comments.
        $diff = array_diff($realNames, $foundParams);
        foreach ($diff as $neededParam) {
            if (count($params) !== 0) {
                $errorPos = ($params[(count($params) - 1) ]->getLine() + $commentStart);
            } else {
                $errorPos = $commentStart;
            }
            $error = 'Doc comment for "%s" missing';
            $data = array($neededParam);
            $this->currentFile->addError($error, $errorPos, 'MissingParamTag', $data);
        }
    }
    protected function isWhitespaceUsedToIntend($content) {
        // is a space char in the indention?
        return preg_match('/[^\t]/', $content) ? TRUE : FALSE;
    }
}
?>