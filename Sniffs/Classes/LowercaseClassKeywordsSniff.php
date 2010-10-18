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
 * TYPO3_Sniffs_Classes_LowercaseClassKeywordsSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category	Classes
 * @package		TYPO3_PHPCS_Pool
 * @author      Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright	Copyright (c) 2010, Andy Grunwald
 * @license		http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version     SVN: $ID$
 * @link		http://pear.typo3.org
 */

/**
 * Ensures all class keywords are lowercase.
 *
 * @category	Classes
 * @package		TYPO3_PHPCS_Pool
 * @author      Andy Grunwald <andreas.grunwald@wmdb.de>
 * @copyright	Copyright (c) 2010, Andy Grunwald
 * @license		http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version     Release: @package_version@
 * @link		http://pear.typo3.org
 */
class TYPO3_Sniffs_Classes_LowercaseClassKeywordsSniff extends Squiz_Sniffs_Classes_LowercaseClassKeywordsSniff {
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(T_CLASS, T_INTERFACE, T_EXTENDS, T_IMPLEMENTS, T_ABSTRACT, T_FINAL, T_VAR, T_CONST, T_PRIVATE, T_PUBLIC, T_PROTECTED);
	}
}
?>