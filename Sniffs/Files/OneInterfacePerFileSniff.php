<?php
/**
 * TYPO3_Sniffs_Files_OneInterfacePerFileSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Files
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks that only one interface is declared per file.
 * The base of this sniff was "Squiz_Sniffs_Classes_ClassDeclarationSniff".
 * Thanks for this!
 *
 * @category  Files
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Files_OneInterfacePerFileSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_INTERFACE);
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
        $nextInterface = $phpcsFile->findNext($this->register(), ($stackPtr + 1));
        if ($nextInterface !== false) {
            $error = 'Only one interface is allowed in a file.';
            $phpcsFile->addError($error, $nextInterface);
        }
    }
}
?>