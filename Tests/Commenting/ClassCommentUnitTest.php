<?php
/**
 * Unit test class for TYPO3_Sniffs_Commenting_ClassCommentSniff.
 *
 * PHP version 5
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Andy Grunwald
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Unit test class for TYPO3_Sniffs_Commenting_ClassCommentSniff.
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Andy Grunwald
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Tests_Commenting_ClassCommentUnitTest extends AbstractSniffUnitTest
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
        if (version_compare(PHP_VERSION, '5.4.0', '<') === true) {
            return array(
                    4   => 1,
                    15  => 1,
                    36  => 1,
                    44  => 1,
                    59  => 1,
                    139 => 1,
                    147 => 1,
                    158 => 1,
                    162 => 1,
                   );
        } else {
            return array(
                    4   => 1,
                    15  => 1,
                    36  => 1,
                    44  => 1,
                    59  => 1,
                    139 => 1,
                    147 => 1,
                    158 => 1,
                    162 => 1,
                    171 => 1,
                    178 => 1,
                    189 => 1,
                    193 => 1,
                    194 => 1,
                   );
        }//end if

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
                44  => 1,
                52  => 1,
                66  => 1,
                79  => 1,
                89  => 1,
                96  => 1,
                106 => 1,
                116 => 1,
                123 => 1,
               );

    }//end getWarningList()


}//end class
