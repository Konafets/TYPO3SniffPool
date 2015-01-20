<?php
/**
 * Unit test class for TYPO3_Sniffs_Commenting_DocCommentSniff.
 *
 * PHP version 5
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Unit test class for TYPO3_Sniffs_Commenting_ClassCommentSniff.
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Tests_Commenting_DocCommentUnitTest extends AbstractSniffUnitTest
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
                34  => 3,
                36  => 2,
                38  => 1,
                42  => 1,
                45  => 1,
                50  => 1,
                53  => 1,
                61  => 1,
                65  => 1,
                74  => 3,
                75  => 3,
                110 => 1,
                119 => 1,
                141 => 1,
                173 => 1,
                177 => 1,
                182 => 1,
                208 => 1,
                209 => 1,
                214 => 1,
                215 => 1,
               );

    }//end getErrorList()


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
                97  => 1,
                119 => 1,
                131 => 2,
                190 => 1,
                192 => 1,
               );

    }//end getWarningList()


}//end class
