<?php
/**
 * Unit test class for the WhitespaceAfterCommentSigns sniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Unit test class for the WhitespaceAfterCommentSigns sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Tests_WhiteSpace_WhitespaceAfterCommentSignsUnitTest extends AbstractSniffUnitTest
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
                    3 => 1,
                    4 => 1,
                    5 => 1,
                    //6 => 1,
                    7 => 0,
                    8 => 0,
                    9 => 0,
                    10 => 0,
                    11 => 0,
                    13 => 0,
                    14 => 0,
                    15 => 0,
                    16 => 0,
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
        return array();
    }
}
?>