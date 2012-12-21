<?php
/**
 * Unit test class for the ValidBreakStatementsInSwitches sniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Unit test class for the ValidBreakStatementsInSwitches sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Tests_ControlStructures_ValidBreakStatementsInSwitchesUnitTest extends AbstractSniffUnitTest
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
                    7 => 1,
                    18 => 1,
                    19 => 1,
                    27 => 1,
                    37 => 0,
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