<?php
/**
 * Unit test class for the ValidVariableName sniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  NamingConventions
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Unit test class for the ValidVariableName sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  NamingConventions
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Tests_NamingConventions_ValidVariableNameUnitTest extends AbstractSniffUnitTest
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
                9 => 0,
                12 => 1,
                13 => 1,
                19 => 1,
                20 => 1,
                21 => 1,
                22 => 1,
                23 => 1,
                66 => 1,
                67 => 1,
                68 => 1,
                69 => 1,
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