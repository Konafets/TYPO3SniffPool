<?php
/**
 * Ensures doc blocks follow basic formatting.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @copyright 2015 Stefano Kowalke
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

/**
 * Ensures doc blocks follow basic formatting.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @copyright 2015 Stefano Kowalke
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_Commenting_DocCommentSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                   'JS',
                                  );

    protected $spaces = 1;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_DOC_COMMENT_OPEN_TAG);

    }//end register()


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
        $tokens       = $phpcsFile->getTokens();
        $commentStart = $stackPtr;
        $commentEnd   = $tokens[$stackPtr]['comment_closer'];

        $empty = array(
                  T_DOC_COMMENT_WHITESPACE,
                  T_DOC_COMMENT_STAR,
                 );

        $short = $phpcsFile->findNext($empty, ($stackPtr + 1), $commentEnd, true);
        if ($short === false) {
            // No content at all.
            $error = 'Doc comment is empty';
            $phpcsFile->addError($error, $stackPtr, 'Empty');
            return;
        }

        // The first line of the comment should just be the /** code.
        if ($tokens[$short]['line'] === $tokens[$stackPtr]['line']) {
            $error = 'The open comment tag must be the only content on the line';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'ContentAfterOpen');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewline($stackPtr);
                $phpcsFile->fixer->addContentBefore($short, '* ');
                $phpcsFile->fixer->endChangeset();
            }
        }

        // The last line of the comment should just be the */ code.
        $prev = $phpcsFile->findPrevious($empty, ($commentEnd - 1), $stackPtr, true);
        if ($tokens[$prev]['line'] === $tokens[$commentEnd]['line']) {
            $error = 'The close comment tag must be the only content on the line';
            $fix   = $phpcsFile->addFixableError($error, $commentEnd, 'ContentBeforeClose');
            if ($fix === true) {
                $phpcsFile->fixer->addNewlineBefore($commentEnd);
            }
        }

        // Check for additional blank lines at the end of the comment.
        if ($tokens[$prev]['line'] < ($tokens[$commentEnd]['line'] - 1)) {
            $error = 'Additional blank lines found at end of doc comment';
            $fix   = $phpcsFile->addFixableError($error, $commentEnd, 'SpacingAfter');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                for ($i = ($prev + 1); $i < $commentEnd; $i++) {
                    if ($tokens[($i + 1)]['line'] === $tokens[$commentEnd]['line']) {
                        break;
                    }

                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }

        // Check for a comment description.
        if ($tokens[$short]['code'] !== T_DOC_COMMENT_STRING) {
            $error = 'Missing short description in doc comment';
            $phpcsFile->addError($error, $stackPtr, 'MissingShort');
            return;
        }

        // No extra newline before short description.
        if ($tokens[$short]['line'] !== ($tokens[$stackPtr]['line'] + 1)) {
            $error = 'Doc comment short description must be on the first line';
            $fix   = $phpcsFile->addFixableError($error, $short, 'SpacingBeforeShort');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                for ($i = $stackPtr; $i < $short; $i++) {
                    if ($tokens[$i]['line'] === $tokens[$stackPtr]['line']) {
                        continue;
                    } else if ($tokens[$i]['line'] === $tokens[$short]['line']) {
                        break;
                    }

                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }

        // Account for the fact that a short description might cover
        // multiple lines.
        $shortContent = $tokens[$short]['content'];
        $shortEnd     = $short;
        for ($i = ($short + 1); $i < $commentEnd; $i++) {
            if ($tokens[$i]['code'] === T_DOC_COMMENT_STRING) {
                if ($tokens[$i]['line'] === ($tokens[$shortEnd]['line'] + 1)) {
                    $shortContent .= $tokens[$i]['content'];
                    $shortEnd      = $i;
                } else {
                    break;
                }
            }
        }

        if (preg_match('/\p{Lu}|\P{L}/u', $shortContent[0]) === 0) {
            $error = 'Doc comment short description must start with a capital letter';
            $phpcsFile->addError($error, $short, 'ShortNotCapital');
        }

        $long = $phpcsFile->findNext($empty, ($shortEnd + 1), ($commentEnd - 1), true);
        if ($long === false) {
            return;
        }

        if ($tokens[$long]['code'] === T_DOC_COMMENT_STRING) {
            if ($tokens[$long]['line'] !== ($tokens[$shortEnd]['line'] + 2)) {
                $error = 'There must be exactly one blank line between descriptions in a doc comment';
                $fix   = $phpcsFile->addFixableError($error, $long, 'SpacingBetween');
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();
                    for ($i = ($shortEnd + 1); $i < $long; $i++) {
                        if ($tokens[$i]['line'] === $tokens[$shortEnd]['line']) {
                            continue;
                        } else if ($tokens[$i]['line'] === ($tokens[$long]['line'] - 1)) {
                            break;
                        }

                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->endChangeset();
                }
            }

            if (preg_match('/\p{Lu}|\P{L}/u', $tokens[$long]['content'][0]) === 0) {
                $error = 'Doc comment long description must start with a capital letter';
                $phpcsFile->addError($error, $long, 'LongNotCapital');
            }
        }//end if

        if (empty($tokens[$commentStart]['comment_tags']) === true) {
            // No tags in the comment.
            return;
        }

        $firstTag = $tokens[$commentStart]['comment_tags'][0];
        $prev     = $phpcsFile->findPrevious($empty, ($firstTag - 1), $stackPtr, true);
        if ($tokens[$firstTag]['line'] !== ($tokens[$prev]['line'] + 2)) {
            $error = 'There must be exactly one blank line before the tags in a doc comment';
            $fix   = $phpcsFile->addFixableError($error, $firstTag, 'SpacingBeforeTags');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                for ($i = ($prev + 1); $i < $firstTag; $i++) {
                    if ($tokens[$i]['line'] === $tokens[$firstTag]['line']) {
                        break;
                    }

                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $indent = str_repeat(' ', $tokens[$stackPtr]['column']);
                $phpcsFile->fixer->addContent($prev, $phpcsFile->eolChar.$indent.'*'.$phpcsFile->eolChar);
                $phpcsFile->fixer->endChangeset();
            }
        }

        // Break out the tags into groups and check alignment within each.
        // A tag group is one where there are no blank lines between tags.
        // The param tag group is special as it requires all @param tags to be inside.
        $tagGroups    = array();
        $groupid      = 0;
        $paramGroupid = null;
        foreach ($tokens[$commentStart]['comment_tags'] as $pos => $tag) {
            if ($pos > 0) {
                $prev = $phpcsFile->findPrevious(
                    T_DOC_COMMENT_STRING,
                    ($tag - 1),
                    $tokens[$commentStart]['comment_tags'][($pos - 1)]
                );

                if ($prev === false) {
                    $prev = $tokens[$commentStart]['comment_tags'][($pos - 1)];
                }

                if ($tokens[$prev]['line'] !== ($tokens[$tag]['line'] - 1)) {
                    $groupid++;
                }
            }

            if ($tokens[$tag]['content'] === '@param') {
                if (($paramGroupid === null
                    && empty($tagGroups[$groupid]) === false)
                    || ($paramGroupid !== null
                    && $paramGroupid !== $groupid)
                ) {
                    $error = 'Parameter tags should be grouped together in a doc commment';
                    $phpcsFile->addWarning($error, $tag, 'ParamGroup');
                }

                if ($paramGroupid === null) {
                    $paramGroupid = $groupid;
                }
            } else if ($groupid === $paramGroupid) {
                $error = 'Tag cannot be grouped with parameter tags in a doc comment';
                $phpcsFile->addError($error, $tag, 'NonParamGroup');
            }//end if

            $tagGroups[$groupid][] = $tag;
        }//end foreach

        foreach ($tagGroups as $group) {
            $maxLength = 0;
            $paddings  = array();
            foreach ($group as $pos => $tag) {
                $tagLength = strlen($tokens[$tag]['content']);
                if ($tagLength > $maxLength) {
                    $maxLength = $tagLength;
                }

                // Check for a value. No value means no padding needed.
                $string = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $tag, $commentEnd);
                if ($string !== false && $tokens[$string]['line'] === $tokens[$tag]['line']) {
                    $paddings[$tag] = strlen($tokens[($tag + 1)]['content']);
                }
            }

            // Check that there was single blank line after the tag block
            // but account for a multi-line tag comments.
            $lastTag = $group[$pos];
            $next    = $phpcsFile->findNext(T_DOC_COMMENT_TAG, ($lastTag + 3), $commentEnd);
            if ($next !== false) {
                $prev = $phpcsFile->findPrevious(array(T_DOC_COMMENT_TAG, T_DOC_COMMENT_STRING), ($next - 1), $commentStart);
                if ($tokens[$next]['line'] !== ($tokens[$prev]['line'] + 2)) {
                    $error = 'There must be a single blank line after a tag group';
                    $fix   = $phpcsFile->addFixableError($error, $lastTag, 'SpacingAfterTagGroup');
                    if ($fix === true) {
                        $phpcsFile->fixer->beginChangeset();
                        for ($i = ($prev + 1); $i < $next; $i++) {
                            if ($tokens[$i]['line'] === $tokens[$next]['line']) {
                                break;
                            }

                            $phpcsFile->fixer->replaceToken($i, '');
                        }

                        $indent = str_repeat(' ', $tokens[$stackPtr]['column']);
                        $phpcsFile->fixer->addContent($prev, $phpcsFile->eolChar.$indent.'*'.$phpcsFile->eolChar);
                        $phpcsFile->fixer->endChangeset();
                    }
                }
            }//end if

        }//end foreach

        // If there is a param group, it needs to be first.
        if ($paramGroupid !== null && $paramGroupid !== 0) {
            $error = 'Parameter tags must be defined first in a doc commment';
            $phpcsFile->addError($error, $tagGroups[$paramGroupid][0], 'ParamNotFirst');
        }

        $foundTags = array();
        foreach ($tokens[$stackPtr]['comment_tags'] as $pos => $tag) {
            $tagName = $tokens[$tag]['content'];
            if (isset($foundTags[$tagName]) === true) {
                $lastTag = $tokens[$stackPtr]['comment_tags'][($pos - 1)];
                if ($tokens[$lastTag]['content'] !== $tagName) {
                    $error = 'Tags should be grouped together in a doc comment';
                    $phpcsFile->addWarning($error, $tag, 'TagsNotGrouped');
                }

                continue;
            }

            $foundTags[$tagName] = true;
        }

        // The data type has to be declared in short form
        // integer -> int; boolean -> bool
        $longTypes = array('boolean', 'integer');
        $shortTypes = array('bool', 'int');
        $fix = false;
        foreach ($tokens[$stackPtr]['comment_tags'] as $pos => $tag) {
            $dataType = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $tag + 1);
            for ($i = 0; $i < count($longTypes); $i++) {
                if (strpos($tokens[$dataType]['content'], $longTypes[$i]) !== FALSE) {
                    $error = 'Use short form of data types; expected "%s" but found "%s".';
                    $data = array($shortTypes[$i], $longTypes[$i]);
                    $fix = $phpcsFile->addFixableError($error, $tag, 'UseShortDataType', $data);
                }
                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken(
                        $dataType,
                        substr_replace($tokens[$dataType]['content'], $shortTypes[$i], 0, strlen($longTypes[$i]))
                    );
                    $fix = false;
                }
            }
        }


        $tags = array();
        foreach ($tokens[$stackPtr]['comment_tags'] as $pos => $tag) {
            $type               = '';
            $typeSpaceContent   = '';
            $isTypeSpaceTab     = false;
            $typeSpaceToken     = 1;
            $typeSpaceLength    = 1;
            $var                = '';
            $isVarSpaceTab      = false;
            $varSpaceLength     = 1;
            $comment            = '';
            $commentLines       = array();
            $isCommentSpaceTab  = false;
            $commentSpaceLength = 1;

//            $line                 = array();
//            $lineEnd = $phpcsFile->findNext(T_DOC_COMMENT_WHITESPACE, ($tag + 1), null, false, "\n", true);
//
//            $line[] = $tokens[$tag]['content'];
//            for ($i = ($tag + 1); $i < $lineEnd; $i++) {
//                $line[] = $tokens[$i]['content'];
//            }

            if ($tokens[($tag + 2)]['code'] === T_DOC_COMMENT_STRING) {
                $matches = array();
                preg_match('/([^$&]+)(?:((?:\$|&)[^\s]+)(?:(\s+)(.*))?)?/', $tokens[($tag + 2)]['content'], $matches);

                $type              = trim($matches[1]);
                $typeSpaceToken    = $tag + 1;
                $typeSpaceContent  = $tokens[$typeSpaceToken]['content'];
                $typeSpaceLength   = strlen($typeSpaceContent);
                $isTypeSpaceTab    = $this->isTabUsedToIntend($typeSpaceContent);

                if (isset($matches[2]) === true) {
                    $var            = $matches[2];
                    $isVarSpaceTab  = $this->isTabUsedToIntend($matches[1]);
                    $varSpaceLength = strlen(ltrim($matches[1], $type));
                }

                if (isset($matches[4]) === true) {
                    $commentSpaceContent = $matches[3];
                    $commentSpaceLength  = strlen($commentSpaceContent);
                    $isCommentSpaceTab   = $this->isTabUsedToIntend($commentSpaceContent);
                    $comment             = $matches[4];
                    $commentLines[]      = array(
                                            'comment' => $comment,
                                            'token'   => ($tag + 2),
                                            'indent'  => $commentSpaceLength,
                                           );

                    // Any strings until the next tag belong to this comment.
                    if (isset($tokens[$commentStart]['comment_tags'][($pos + 1)]) === true) {
                        $end = $tokens[$commentStart]['comment_tags'][($pos + 1)];
                    } else {
                        $end = $tokens[$commentStart]['comment_closer'];
                    }

                    for ($i = ($tag + 3); $i < $end; $i++) {
                        if ($tokens[$i]['code'] === T_DOC_COMMENT_STRING) {
                            $indent = 0;
                            if ($tokens[($i - 1)]['code'] === T_DOC_COMMENT_WHITESPACE) {
                                $indent = strlen($tokens[($i - 1)]['content']);
                            }

                            $comment       .= ' '.$tokens[$i]['content'];
                            $commentLines[] = array(
                                               'comment' => $tokens[$i]['content'],
                                               'token'   => $i,
                                               'indent'  => $indent,
                                              );
                        }
                    }
                } else {
                    $commentLines[] = array('comment' => '');
                }//end if
            }

            $tags[] = array(
                'tag' => array(
                    'token'   => $tag,
                    'content' => $tokens[$tag]['content'],
                ),
                'type' => array(
                    'space'       => $typeSpaceContent,
                    'spaceToken'  => $typeSpaceToken,
                    'spaceLength' => $typeSpaceLength,
                    'spaceIsTab'  => $isTypeSpaceTab,
                    'content'     => $type,
                ),
                'var' => array(
                    'spaceLength' => $varSpaceLength,
                    'spaceIsTab'  => $isVarSpaceTab,
                    'content'     => $var,
                ),
                'comment' => array(
                    'spaceLength' => $commentSpaceLength,
                    'content'     => $comment,
                    'lines'       => $commentLines,
                    'spaceIsTab'  => $isCommentSpaceTab
                ),
            );
        }

        foreach ($tags as $pos => $tag) {
            // Make sure that there are only spaces used to intend the var type.
            if ($tag['type']['spaceIsTab'] === true) {
                $error = 'Spaces must be used to indent variable type. Tabs found.';
                $fix = $phpcsFile->addFixableError($error, $tag['tag']['token'], 'TabIndentVariableType');

                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken($tag['type']['spaceToken'], str_repeat(' ', $this->spaces));
                }
            }

            // Check number of spaces before the type.
            if ($tag['type']['spaceLength'] > 1) {
                $error = 'Expected 1 space after the %s tag; %s found';
                $data = array($tag['tag']['content'], $tag['type']['spaceLength']);
                $fix = $phpcsFile->addFixableWarning($error, $tag['tag']['token'], 'SpacingBeforeParamType', $data);
                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken($tag['type']['spaceToken'], str_repeat(' ', $this->spaces));
                }
            }

            switch ($tag['tag']['content']) {
                case '@param':
                case '@property':
                case '@property-write':
                case '@property-read':
                    $this->processParams($phpcsFile, $tag);
                    break;
                case '@author':

                    break;

                case '@return':
                    $this->processReturn($phpcsFile, $tag['tag']['token'], $tokens);
                    break;

                case '@see':

                    break;

                case '@throw':

                    break;

                default:
                    break;
            }
        }
    }//end process()

    /**
     * Process the function parameter comments.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param                      $tag
     *
     * @return void
     */
    protected function processParams(PHP_CodeSniffer_File $phpcsFile, $tag)
    {
        // If the type is empty, the whole line is empty.
        if ($tag['type']['content'] === '') {
            return;
        }

        if ($tag['var']['content'] === '') {
            return;
        }

        // Make sure that there are only spaces used to intend the var name.
        if ($tag['var']['spaceIsTab'] === true) {
            $error = 'Spaces must be used to indent the variable name. Tabs found.';
            $fix = $phpcsFile->addFixableError($error, $tag['tag']['token'], 'TabIndentVariableName');

            if ($fix === true) {
                $this->rewriteSpaceAfterVariableType($phpcsFile, $tag, $this->spaces);
            }
        }

        // Check number of spaces before the variable name
        if ($tag['var']['spaceLength'] !== $this->spaces) {
            $error = 'Expected %s spaces after parameter type; %s found';
            $data  = array(
                      $this->spaces,
                      $tag['var']['spaceLength'],
                     );

            $fix = $phpcsFile->addFixableError($error, $tag['tag']['token'], 'SpacingAfterParamType', $data);
            if ($fix === true) {
                $this->rewriteSpaceAfterVariableType($phpcsFile, $tag, $this->spaces);
            }
        }

        if ($tag['comment']['content'] === '') {
            return;
        }

        // Make sure that there are only spaces used to intend the var comment.
        if ($tag['comment']['spaceIsTab'] === true) {
            $error = 'Spaces must be used to indent comment. Tabs found.';
            $fix = $phpcsFile->addFixableError($error, $tag['tag']['token'], 'TabIndentVariableComment');
            if ($fix === true) {
                $this->rewriteSpaceAfterVariableName($phpcsFile, $tag, $this->spaces);
            }
        }

        // Check number of spaces after the var name.
        if ($tag['comment']['spaceLength'] !== $this->spaces) {
            $error = 'Expected 1 space after parameter name; %s found';
            $data  = array($tag['comment']['spaceLength']);
            $fix = $phpcsFile->addFixableWarning($error, $tag['tag']['token'], 'SpacingAfterParamName', $data);
            if ($fix === true) {
                $this->rewriteSpaceAfterVariableName($phpcsFile, $tag, $this->spaces);
            }
        }
    }//end processParams()

    /**
     * Process the return comment of this function comment.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $return
     * @param array                $tokens
     *
     * @return void
     */
    protected function processReturn(PHP_CodeSniffer_File $phpcsFile, $return, array $tokens)
    {
        if ($tokens[($return + 1)]['code'] === T_DOC_COMMENT_WHITESPACE) {
            if ($this->isTabUsedToIntend($tokens[($return + 1)]['content']) === true) {
                $error = 'Only spaces are allowed between @return and type; tabs found';

                $fix = $phpcsFile->addFixableError($error, $return, 'ReturnSpacingTab');

                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken(($return + 1), str_repeat(' ', $this->spaces));
                }
            }

            $spaceLength = strlen($tokens[($return + 1)]['content']);
            if ($spaceLength > $this->spaces) {
                $error = 'Only %s space allowed between @return and type; % spaces found';
                $data = array(
                    $this->spaces,
                    $spaceLength
                );

                $fix = $phpcsFile->addFixableError($error, $return, 'ReturnSpacing', $data);

                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken(($return + 1), str_repeat(' ', $this->spaces));
                }
            }
        }
    }//end processReturn

    /**
     * @param \PHP_CodeSniffer_File $phpcsFile
     * @param array                 $tag
     *
     * @return array
     */
    protected function rewriteSpaceAfterVariableType(PHP_CodeSniffer_File $phpcsFile, $tag)
    {
        $phpcsFile->fixer->beginChangeset();

        $spaceContent = $tag['comment']['spaceIsTab'] ? "\t" : ' ';

        $content = $tag['type']['content'];
        $content .= str_repeat(' ', $this->spaces);
        $content .= $tag['var']['content'];
        $content .= str_repeat($spaceContent, $tag['comment']['spaceLength']);
        $content .= $tag['comment']['lines'][0]['comment'];
        $phpcsFile->fixer->replaceToken(($tag['tag']['token'] + 2), $content);

        // Fix up the indent of additional comment lines.
        foreach ($tag['comment']['lines'] as $lineNum => $line) {
            if ($lineNum === 0
                || $tag['comment']['lines'][$lineNum]['indent'] === 0
            ) {
                continue;
            }

            $newIndent = ($tag['comment']['lines'][$lineNum]['indent'] + $this->spaces
                - $tag['type']['spaceLength']);
            $phpcsFile->fixer->replaceToken(
                ($tag['comment']['lines'][$lineNum]['token'] - 1),
                str_repeat(' ', $newIndent)
            );
        }

        $phpcsFile->fixer->endChangeset();
    }//end rewriteSpaceAfterVariableType()

    /**
     * @param \PHP_CodeSniffer_File $phpcsFile
     * @param array                 $tag
     * @param integer               $spaces
     */
    public function rewriteSpaceAfterVariableName(PHP_CodeSniffer_File $phpcsFile, $tag, $spaces)
    {
        $phpcsFile->fixer->beginChangeset();

        $spaceContent = $tag['var']['spaceIsTab'] ? "\t" : ' ';

        $content  = $tag['type']['content'];
        $content .= str_repeat($spaceContent, $tag['var']['spaceLength']);
        $content .= $tag['var']['content'];
        $content .= str_repeat(' ', $spaces);
        $content .= $tag['comment']['lines'][0]['comment'];
        $phpcsFile->fixer->replaceToken(($tag['tag']['token'] + 2), $content);

        // Fix up the indent of additional comment lines.
        foreach ($tag['comment']['lines'] as $lineNum => $line) {
            if ($lineNum === 0
                || $tag['comment']['lines'][$lineNum]['indent'] === 0
            ) {
                continue;
            }

            $newIndent = ($tag['comment']['lines'][$lineNum]['indent'] + $spaces - $tag['var']['spaceLength']);
            $phpcsFile->fixer->replaceToken(
                ($tag['comment']['lines'][$lineNum]['token'] - 1),
                str_repeat(' ', $newIndent)
            );
        }

        $phpcsFile->fixer->endChangeset();
    }//end rewriteSpaceAfterVariableName()

    /**
     * Checks if the parameter contain a tab char
     *
     * @param string $content The whitespace part inside the comment
     *
     * @return boolean
     */
    protected function isTabUsedToIntend($content)
    {
        return preg_match('/[\t]/', $content) ? true : false;
    }//end isTabUsedToIntend()

}//end class
