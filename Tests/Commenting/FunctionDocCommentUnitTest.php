<?php
/**
 * Unit test class for FunctionDocCommentSniff.
 *
 * PHP version 5
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Unit test class for FunctionDocCommentSniff.
 *
 * This unit test was copied and modified
 * from PEAR.Commenting.FunctionCommentSniff.
 * Thanks for this guys!
 *
 * @category  Commenting
 * @package   TYPO3SniffPool
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Tests_Commenting_FunctionDocCommentUnitTest extends AbstractSniffUnitTest
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
                12  => 1,
                19  => 2,
                24  => 2,
                25  => 2,
                27  => 1,
                28  => 1,
                39  => 1,
                94  => 1,
                99  => 1,
                102 => 1,
                111 => 1,
                113 => 2,
                114 => 1,
                115 => 1,
                116 => 1,
                117 => 1,
                132 => 1,
                135 => 2,
                153 => 1,
                160 => 1,
                170 => 1,
                176 => 2,
                191 => 1,
                203 => 1,
                221 => 1,
                229 => 1,
                253 => 1,
                254 => 2,
                255 => 1,
                256 => 1,
                257 => 1,
                258 => 1,
                259 => 1,
                264 => 1,
                272 => 1,
                281 => 1,
                287 => 1,
                293 => 1,
                297 => 2,
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
                  13  => 1,
                  127 => 1,
                  179 => 1,
                  230 => 1,
               );

    }//end getWarningList()


}//end class
