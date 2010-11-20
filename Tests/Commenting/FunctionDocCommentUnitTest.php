<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2010 Andy Grunwald <andreas.grunwald@wmdb.de>
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
 * Unit test class for FunctionDocCommentSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category	Commenting
 * @package		TYPO3_PHPCS_Pool
 * @author		Greg Sherwood <gsherwood@squiz.net>
 * @author		Marc McIntyre <mmcintyre@squiz.net>
 * @author		Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright	Copyright (c) 2010, Andy Grunwald
 * @license		http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version		SVN: $ID$
 * @link		http://pear.typo3.org
 */
/**
 * Unit test class for FunctionDocCommentSniff.
 *
 * This unit test was copied and modified
 * from PEAR.Commenting.FunctionCommentSniff.
 * Thanks for this guys!
 *
 * @category	Commenting
 * @package		TYPO3_PHPCS_Pool
 * @author		Greg Sherwood <gsherwood@squiz.net>
 * @author		Marc McIntyre <mmcintyre@squiz.net>
 * @author		Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright	Copyright (c) 2010, Andy Grunwald
 * @license		http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version		Release: @package_version@
 * @link		http://pear.typo3.org
 */
class TYPO3_Tests_Commenting_FunctionDocCommentUnitTest extends AbstractSniffUnitTest {
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getErrorList() {
        return array(
                9   => 1,
                10  => 1,
                12  => 1,
                13  => 1,
                24  => 1,
                30  => 1,
                33  => 1,
                47  => 1,
                68  => 1,
                78  => 1,
                92  => 1,
                97  => 1,
                100 => 1,
                109 => 1,
                110 => 1,
                111 => 1,
                112 => 1,
                113 => 2,
                114 => 2,
                115 => 4,
                125 => 1,
                126 => 1,
                127 => 1,
                128 => 3,
                141 => 2,
                150 => 1,
                157 => 1,
                167 => 1,
                176 => 3,
                186 => 1,
                197 => 1,
                218 => 1,
                250 => 3,
                251 => 1,
                252 => 1,
                253 => 2,
                254 => 2,
                255 => 4,
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
    public function getWarningList() {
        return array();
    }
}
?>