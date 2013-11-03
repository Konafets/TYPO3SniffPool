<?php
/**
 * TYPO3SniffPool_Sniffs_ControlStructures_SwitchDeclarationSniff.
 *
 * PHP version 5
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2013 Stefano Kowalke
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

/**
 * TYPO3SniffPool_Sniffs_ControlStructures_SwitchDeclarationSniff.
 *
 * Ensures all the breaks and cases are aligned correctly according to their
 * parent switch's alignment and enforces other switch formatting.
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2013 Stefano Kowalke
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_ControlStructures_SwitchDeclarationSniff implements PHP_CodeSniffer_Sniff
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

    /**
     * The number of tabs code should be indented.
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
        return array(T_SWITCH);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // We can't process SWITCH statements unless we know where they start and end.
        if (isset($tokens[$stackPtr]['scope_opener']) === false
            || isset($tokens[$stackPtr]['scope_closer']) === false
        ) {
            return;
        }

        $switch         = $tokens[$stackPtr];
        $nextCase       = $stackPtr;
        $caseAlignment  = ($switch['column'] + $this->indent);
        $caseCount      = 0;
        $breakAlignment = $caseAlignment + $this->indent;
        $foundDefault   = false;

        while (($nextCase = $phpcsFile->findNext(array(T_CASE, T_DEFAULT, T_SWITCH), ($nextCase + 1), $switch['scope_closer'])) !== false) {
            // Skip nested SWITCH statements; they are handled on their own.
            if ($tokens[$nextCase]['code'] === T_SWITCH) {
                $nextCase = $tokens[$nextCase]['scope_closer'];
                continue;
            }

            if ($tokens[$nextCase]['code'] === T_DEFAULT) {
                $type         = 'Default';
                $foundDefault = true;
            } else {
                $type = 'Case';
                $caseCount++;
            }

            if ($tokens[$nextCase]['content'] !== strtolower($tokens[$nextCase]['content'])) {
                $expected = strtolower($tokens[$nextCase]['content']);
                $error    = '%s keyword must be lowercase; expected "%s" but found "%s"';
                $data     = array(
                             strtoupper($type),
                             $expected,
                             $tokens[$nextCase]['content'],
                            );
                $phpcsFile->addError($error, $nextCase, $type.'NotLower', $data);
            }

            if ($tokens[$nextCase]['column'] !== $caseAlignment) {
                $error = '%s keyword must be indented %s tab from SWITCH keyword';
                $data = array(
                            strtoupper($type),
                            $this->indent
                        );
                $phpcsFile->addError($error, $nextCase, $type.'Indent', $data);
            }


            if ($type === 'Case'
                && ($tokens[($nextCase + 1)]['type'] !== 'T_WHITESPACE'
                || $tokens[($nextCase + 1)]['content'] !== ' ')
            ) {
                $error = 'CASE keyword should be followed by a single space';
                $phpcsFile->addError($error, $nextCase, 'SpacingAfterCase');
            }

            $opener = $tokens[$nextCase]['scope_opener'];
            if ($tokens[($opener - 1)]['type'] === 'T_WHITESPACE') {
                $error = 'There must be no space before the colon in a %s statement';
                $data = array(strtoupper($type),);
                $phpcsFile->addError(
                    $error, $nextCase, 'SpaceBeforeColon'.$type, $data
                );
            }

            // Falling through a case have to be indicated by a comment
            $afterNextCase = $phpcsFile->findNext(T_CASE, $nextCase + 1);
            if (($afterNextCase)
                && ($type !== 'Default')
                && (($tokens[$nextCase]['scope_closer']) === ($tokens[$afterNextCase]['scope_closer']))
            ) {
                $commentBeforeCase = $phpcsFile->findPrevious(
                    T_COMMENT, $afterNextCase, $nextCase
                );
                if (($tokens[$commentBeforeCase]['line']) !== ($tokens[$afterNextCase]['line'] - 1)) {
                    $error = 'If one case block has to pass control into another case block without having a break, there must be a comment about it in the code.';
                    $phpcsFile->addError(
                        $error, $nextCase, 'CaseWithoutBreakNoCommentFound'
                    );
                }
            }

            $nextBreak = $tokens[$nextCase]['scope_closer'];
            if ($type === 'Default') {
                if ($tokens[$nextCase]['scope_closer'] !== $switch['scope_closer']) {
                    $error = 'The "default" statement must be the last in the switch.';
                    $phpcsFile->addError($error, $nextCase, 'DefaultNotLastInSwitch');
                } else if ($tokens[$nextBreak]['code'] === T_BREAK) {
                    $error = 'The "default" statement must not have a "break" statement.';
                    $phpcsFile->addError($error, $nextCase, 'DefaultNoBreak');
                }
            } else if ($tokens[$nextBreak]['code'] === T_BREAK
                || $tokens[$nextBreak]['code'] === T_RETURN
                || $tokens[$nextBreak]['code'] === T_CONTINUE
                || $tokens[$nextBreak]['code'] === T_THROW
            ) {
                if ($tokens[$nextBreak]['scope_condition'] === $nextCase) {
                    if ($tokens[$nextBreak]['column'] !== $breakAlignment) {
                        $error = 'Case breaking statement must be indented %s tab from CASE keyword';
                        $data = array($this->indent);
                        $phpcsFile->addError(
                            $error, $nextBreak, 'BreakIndent', $data
                        );
                    }

                    $breakLine = $tokens[$nextBreak]['line'];
                    $prevLine  = 0;
                    for ($i = ($nextBreak - 1); $i > $stackPtr; $i--) {
                        if ($tokens[$i]['type'] !== 'T_WHITESPACE') {
                            $prevLine = $tokens[$i]['line'];
                            break;
                        }
                    }

                    if ($prevLine !== ($breakLine - 1)) {
                        $error = 'There should no blank lines before case breaking statements';
                        $phpcsFile->addWarning(
                            $error, $nextBreak, 'SpacingBeforeBreak'
                        );
                    }

                    $semicolon = $phpcsFile->findNext(T_SEMICOLON, $nextBreak);
                    for ($i = ($semicolon + 1); $i < $tokens[$stackPtr]['scope_closer']; $i++) {
                        if ($tokens[$i]['type'] === 'T_BREAK') {
                            $error = 'Only one break statement is allowed per case.';
                            $phpcsFile->addError(
                                $error, $i, 'FoundMultipleBreaksPerCase'
                            );
                        }
                        if ($tokens[$i]['type'] !== 'T_WHITESPACE') {
                            break;
                        }
                    }

                    $caseLine = $tokens[$nextCase]['line'];
                    $nextLine = $tokens[$nextBreak]['line'];
                    for ($i = ($opener + 1); $i < $nextBreak; $i++) {
                        if ($tokens[$i]['type'] !== 'T_WHITESPACE') {
                            $nextLine = $tokens[$i]['line'];
                            if ($tokens[$i]['type'] === 'T_CASE') {
                                break;
                            }
                            if (($tokens[$nextCase]['column'] + 1) !== ($tokens[$i]['column'])) {
                               $error = 'The code inside the case statemens is further indented with a single tab';
                               $phpcsFile->addError($error, $i, 'CodeNotCorrectlyAligned');
                            }
                            break;
                        }
                    }

                    if ($nextLine !== ($caseLine + 1)) {
                        $error = 'There should be no blank lines after %s statements';
                        $data = array(strtoupper($type));
                        $phpcsFile->addWarning(
                            $error, $nextCase, 'SpacingAfter'.$type, $data
                        );
                    }
                }//end if
            }// end if
        }//end while

        if ($foundDefault === false) {
            $error = 'All SWITCH statements must contain a DEFAULT case';
            $phpcsFile->addError($error, $stackPtr, 'MissingDefault');
        }

        if ($tokens[$switch['scope_closer']]['column'] !== $switch['column']) {
            $error = 'Closing brace of SWITCH statement must be aligned with SWITCH keyword';
            $phpcsFile->addError($error, $switch['scope_closer'], 'CloseBraceAlign');
        }

        if ($caseCount === 0) {
            $error = 'SWITCH statements must contain at least one CASE statement';
            $phpcsFile->addError($error, $stackPtr, 'MissingCase');
        }
    }//end process()


}//end class

?>
