<?php
/**
 * TYPO3_Sniffs_Commenting_SpaceAfterDoubleSlashSniff.
 *
 * PHP version 5
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Checks that the include_once is used in all cases.
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_Commenting_SpaceAfterDoubleSlashSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of tokenizers this sniff supports
     *
     * @var array
     */
    public $supportedTokenizes = array('PHP');


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_COMMENT);

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
        $tokens  = $phpcsFile->getTokens();
        $keyword = $tokens[$stackPtr]['content'];
        if (substr($keyword, 0, 2) === '//' && (substr($keyword, 2, 1) === ' ') === false) {
            $error = 'Space must be added in single line comments after the comment sign (double slash).';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceAfterDoubleSlash');
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($stackPtr, preg_replace('#^//#', '// ', $keyword));
            }
        }

    }//end process()


}//end class
