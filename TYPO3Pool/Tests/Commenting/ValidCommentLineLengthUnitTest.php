<?php
/**
 * Unit test class for the ValidCommentLineLength sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Unit test class for the ValidCommentLineLength sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Tests_Commenting_ValidCommentLineLengthUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getErrorList()
    {
        return array(
                2 => 0,
                3 => 0,
                4 => 0,
                6 => 0,
                8 => 0,
                );
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getWarningList()
    {
        return array(
            5 => 1,
            9 => 1,
        );
    }
}
?>