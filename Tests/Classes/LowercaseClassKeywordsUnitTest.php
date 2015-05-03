<?php
/**
 * Unit test class for the LowercaseClassKeywords sniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Classes
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Unit test class for the LowercaseClassKeywords sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  Classes
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Tests_Classes_LowercaseClassKeywordsUnitTest extends AbstractSniffUnitTest
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
        $errors = array(
                2 => 1,
                3 => 1,
                4 => 1,
                5 => 1,
                6 => 1,
                7 => 1,
                10 => 1,
                14 => 1,
                18 => 1,
                22 => 1,
                26 => 1,
                );

        // The trait test will only work in PHP versions where traits exist.
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $errors[30] = 1;
        }

        return $errors;
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