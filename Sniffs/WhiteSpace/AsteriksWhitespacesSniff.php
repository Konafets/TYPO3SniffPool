<?php
/**
 * TYPO3_Sniffs_WhiteSpace_AsteriksWhitespacesSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that one whitespace is after an asteriks charakter in comments
 *
 * Correct:   * @author Laura Thewalt
 * Incorrect: *@author Laura Thewalt
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_WhiteSpace_AsteriksWhitespacesSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_DOC_COMMENT);
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
        $content = trim($tokens[$stackPtr]['content']);
        // We ignore empty lines in doc comment
        if (preg_match('/^\*\s{0,}$/', $content) == 1) {
            return;
        }
        if ((strpos($content, '*') === 0) && (strpos($content, ' ') != 1) && (strpos($content, '/') != 1) && (strpos($content, "\n") != 1)) {
            $phpcsFile->addError('Whitespace must be added after single asteriks expected "* ' . ltrim($content, '*') . '" found "' . $content . '"', $stackPtr);
        }
    }
}
?>