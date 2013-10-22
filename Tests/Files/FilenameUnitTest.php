<?php
/**
 * Unit test class for Filename sniff.
 *
 * PHP version 5
 *
 * @category  Files
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2013 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */

/**
 * Unit test class for the Filename sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  Files
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2013 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class TYPO3SniffPool_Tests_Files_FilenameUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $testFile
     *
     * @return array(int => int)
     */
    public function getErrorList($testFile='')
    {
        switch ($testFile) {
        case 'FilenameUnitTest.1.inc':
            return array(
                    2 => 1,
                   );
            break;
        case 'FilenameUnitTest.2.inc':
            return array(
                    2 => 1,
                   );
            break;
        default:
            return array();
            break;
        }//end switch
    }//end getErrorList()

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $testFile
     *
     * @return array(int => int)
     */
    public function getWarningList($testFile='')
    {
        switch ($testFile) {
        case 'FilenameUnitTest.3.inc':
            return array(
                    2 => 0,
                   );
            break;
        default:
            return array();
            break;
        }//end switch

    }//end getWarningList()
    
}//end class

?>
