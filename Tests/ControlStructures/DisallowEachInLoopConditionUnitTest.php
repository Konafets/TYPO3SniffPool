<?php
/**
 * Unit test class for the DisallowEachInLoopCondition sniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Unit test class for the DisallowEachInLoopCondition sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  ControlStructures
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2012 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Tests_ControlStructures_DisallowEachInLoopConditionUnitTest extends AbstractSniffUnitTest
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
            2 => 1,
            6 => 0,
            12 => 1,
            16 => 0,
        );
    } //end getErrorList()

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
    } //end getWarningList()

} //end class

?>