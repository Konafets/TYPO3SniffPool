<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2010 Stefano Kowalke <blueduck@gmx.net>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Unit test class for the UnnecessaryStringConcat sniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Strings
 * @package   TYPO3_PHPCS_Pool
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version	  SVN: $ID$
 * @link      http://pear.typo3.org
 */
/**
 * Unit test class for the UnnecessaryStringConcat sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  Strings
 * @package   TYPO3_PHPCS_Pool
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Tests_Strings_UnnecessaryStringConcatUnitTest extends AbstractSniffUnitTest {
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $testFile The name of the file being tested.
     *
     * @return array(int => int)
     */
    public function getErrorList($testFile = 'UnnecessaryStringConcatUnitTest.inc') {
        switch ($testFile) {
            case 'UnnecessaryStringConcatUnitTest.inc':
                return array(2 => 1, 6 => 1, 9 => 1, 12 => 1, 14 => 0, 17 => 1,);
            break;
            case 'UnnecessaryStringConcatUnitTest.js':
                return array(1 => 1, 8 => 1, 11 => 1,);
            break;
            default:
                return array();
            break;
        }
    } //end getErrorList()
    
    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getWarningList() {
        return array();
    } //end getWarningList()
    
} //end class

?>
