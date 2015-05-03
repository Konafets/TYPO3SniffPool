<?php
/**
 * Unit test class for the AlwaysReturn sniff.
 *
 * PHP version 5
 *
 * @category  Scope
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Unit test class for the AlwaysReturn sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  Scope
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Tests_Scope_AlwaysReturnUnitTest extends AbstractSniffUnitTest
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
                    17 => 1,
                    51 => 0,
                    55 => 1,
                    69 => 1,
                    83 => 1,
                    95 => 0,
                    103 => 0,
                    118 => 0,
                    131 => 0,
                    145 => 0,
                    160 => 0,
                    176 => 1,
                    192 => 1,
                    204 => 0,
                    212 => 1,
                    224 => 1,
                    237 => 1,
                    252 => 1,
                    260 => 1,
                    270 => 1,
                    285 => 1,
                    305 => 0,
                    327 => 0,
                    354 => 0,
                    360 => 1,
                    366 => 1,
                    376 => 0,
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
