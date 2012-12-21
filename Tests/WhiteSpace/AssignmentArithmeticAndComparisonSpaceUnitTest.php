<?php
/**
 * Unit test class for the AssignmentArithmeticAndComparisonSpace sniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Unit test class for the AssignmentArithmeticAndComparisonSpace sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  Whitespace
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Tests_WhiteSpace_AssignmentArithmeticAndComparisonSpaceUnitTest extends AbstractSniffUnitTest
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
                    4 => 1,
                    5 => 1,
                    6 => 1,
                    10 => 1,
                    11 => 1,
                    12 => 1,
                    16 => 1,
                    17 => 1,
                    18 => 1,
                    22 => 1,
                    23 => 1,
                    24 => 1,
                    28 => 1,
                    29 => 1,
                    30 => 1,
                    34 => 1,
                    35 => 1,
                    36 => 1,
                    40 => 1,
                    41 => 1,
                    42 => 1,
                    46 => 1,
                    47 => 1,
                    48 => 1,
                    52 => 1,
                    53 => 1,
                    54 => 1,
                    58 => 1,
                    59 => 1,
                    60 => 1,
                    64 => 1,
                    65 => 1,
                    66 => 1,
                    70 => 1,
                    71 => 1,
                    72 => 1,
                    76 => 1,
                    77 => 1,
                    78 => 1,
                    82 => 1,
                    83 => 1,
                    84 => 1,
                    88 => 1,
                    89 => 1,
                    90 => 1,
                    94 => 1,
                    95 => 1,
                    96 => 1,
                    100 => 1,
                    101 => 1,
                    102 => 1,
                    106 => 1,
                    107 => 1,
                    108 => 1,
                    112 => 1,
                    113 => 1,
                    114 => 1,
                    118 => 1,
                    119 => 1,
                    120 => 1,
                    124 => 1,
                    125 => 1,
                    126 => 1,
                    128 => 3,
                    129 => 3,
                    130 => 1,
                    131 => 2,
                    135 => 1,
                    136 => 1,
                    137 => 1,
                    141 => 1,
                    142 => 1,
                    143 => 1,
                    148 => 1,
                    152 => 1,
                    153 => 1,
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