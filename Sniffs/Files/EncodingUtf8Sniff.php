<?php
/**
 * TYPO3_Sniffs_Files_EncodingUtf8.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Files
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Checks the file encoding for UTF-8.
 *
 * @category  Files
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Files_EncodingUtf8Sniff implements PHP_CodeSniffer_Sniff
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
        return array(T_OPEN_TAG);
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
        // Make sure this is the first PHP open tag so we don't process
        // the same file twice.
        $prevOpenTag = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
        if ($prevOpenTag !== false) {
            return;
        }
        $fileContent = file_get_contents($phpcsFile->getFilename());
        $encoding = mb_detect_encoding($fileContent, 'UTF-8, ASCII, ISO-8859-1');
        if ($encoding !== 'UTF-8') {
            $error = 'TYPO3 PHP files use UTF-8 character set; but %s found.';
            $phpcsFile->addError($error, $stackPtr, 'FileIsNotUTF8Encoded', array($encoding));
        }
    }
}
?>