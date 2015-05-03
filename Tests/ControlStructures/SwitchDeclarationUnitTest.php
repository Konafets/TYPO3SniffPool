<?php
/**
 * Unit test class for the SwitchDeclaration sniff.
 *
 * PHP version 5
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2013 Stefano Kowalke
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

/**
 * Unit test class for the SwitchDeclaration sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  ControlStructures
 * @package   TYPO3SniffPool
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2013-2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Tests_ControlStructures_SwitchDeclarationUnitTest extends AbstractSniffUnitTest
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
                31  => 1,
                33  => 2,
                36  => 2,
                37  => 1,
                39  => 2,
                51  => 1,
                54  => 1,
                65  => 1,
                77  => 1,
                82  => 1,
                102 => 1,
                111 => 1,
                186 => 1,
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
                131 => 1,
                138 => 1,
               );

    }//end getWarningList()


}//end class
