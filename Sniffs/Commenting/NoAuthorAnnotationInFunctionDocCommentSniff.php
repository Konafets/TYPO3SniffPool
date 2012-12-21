<?php
/**
 * TYPO3_Sniffs_Commenting_NoAuthorAnnotationInFunctionDocCommentSniff
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   SVN: $Id$
 * @link      http://pear.typo3.org
 */
if (class_exists('PHP_CodeSniffer_CommentParser_FunctionCommentParser', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_CommentParser_FunctionCommentParser not found');
}
/**
 * Parses and verifies that a function / method doc comment
 * has no @author annotation.
 *
 * The part to detect the right comment is copied and modified
 * from PEAR_Sniffs_Commenting_FunctionCommentSniff.
 * Thanks for this guys!
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Commenting_NoAuthorAnnotationInFunctionDocCommentSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * The function comment parser for the current method.
     *
     * @var PHP_CodeSniffer_Comment_Parser_FunctionCommentParser
     */
    protected $commentParser = null;

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
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $find = array(T_COMMENT, T_DOC_COMMENT, T_CLASS, T_FUNCTION, T_OPEN_TAG,);
        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        if ($commentEnd === false) {
            return;
        }
        $tokens = $phpcsFile->getTokens();
        // If the token that we found was a class or a function, then this
        // function has no doc comment.
        $code = $tokens[$commentEnd]['code'];
        if ($code === T_COMMENT) {
            return;
        } elseif ($code !== T_DOC_COMMENT) {
            return;
        }
        // If there is any code between the function keyword and the doc block
        // then the doc block is not for us.
        $ignore = PHP_CodeSniffer_Tokens::$scopeModifiers;
        $ignore[] = T_STATIC;
        $ignore[] = T_WHITESPACE;
        $ignore[] = T_ABSTRACT;
        $ignore[] = T_FINAL;
        $prevToken = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), null, true);
        if ($prevToken !== $commentEnd) {
            return;
        }
        // If the first T_OPEN_TAG is right before the comment, it is probably
        // a file comment.
        $commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($commentStart - 1), null, true);
        if ($tokens[$prevToken]['code'] === T_OPEN_TAG) {
            // Is this the first open tag?
            if ($stackPtr === 0 || $phpcsFile->findPrevious(T_OPEN_TAG, ($prevToken - 1)) === false) {
                return;
            }
        }
        $comment = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));
        try {
            $this->commentParser = new PHP_CodeSniffer_CommentParser_FunctionCommentParser($comment, $phpcsFile);
            $this->commentParser->parse();
        }
        catch(PHP_CodeSniffer_CommentParser_ParserException $e) {
            return;
        }
        // Function doc comment is empty
        $comment = $this->commentParser->getComment();
        if (is_null($comment) === true) {
            return;
        }
        /**
         * Here we go.
         * Now we have a doc comment. Parse the annotations for @author-Tags!
         */
        $error = '@author tag should not be used in function or method phpDoc comment blocks - only at class level';
        $unknownTags = $this->commentParser->getUnknown();
        foreach ($unknownTags as $tagInfo) {
            if ($tagInfo['tag'] !== 'author') {
                continue;
            }
            $phpcsFile->addError($error, $tagInfo['line'] + $commentStart, 'NoAuthorAnnotationInFunctionDocComment');
        }
        return null;
    }
}
?>