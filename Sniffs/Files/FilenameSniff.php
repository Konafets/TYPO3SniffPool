<?php
/**
 * TYPO3_Sniffs_Files_FilenameSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  PHP
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2013 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * DESCRIPTION
 *
 * @category  PHP
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2013 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Files_FilenameSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);
    } //end register()

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
        $findTokens = array(
                       T_CLASS,
                       T_INTERFACE,
                       T_CLOSE_TAG,
                      );

        $stackPtr = $phpcsFile->findNext($findTokens, ($stackPtr + 1));
        $classNameToken = $phpcsFile->findNext(T_STRING, $stackPtr);
        $className      = $tokens[$classNameToken]['content'];
        $fileName = explode('.', $phpcsFile->getFileName());
        $fileName = basename($fileName[0]);
        if ($fileName !== $className) {
            $error = 'The classname is not equal to the filename; Found classname "%s" and filename "%s"';
            $data = array(
                        $className,
                        $fileName
                    );
            $phpcsFile->addError($error, $stackPtr, 'ClassnameNotEqualToFilename', $data);
        }
    } //end process()

} //end class

?>