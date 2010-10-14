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
if (class_exists('PHP_CodeSniffer_Standards_CodingStandard', TRUE) === FALSE) {
	throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}
/**
 * This class register the TYPO Coding Guideline as an standard for the
 * PHPCodeSniffer tool
 *
 * To check the code against the TYPO3 CGL, add --standard=TYPO3 as parameter to
 * phpcs
 *
 * @package TYPO3
 * @subpackage PHPCodeSniffer
 *
 * @author Stefano Kowalke <blueduck@gmx.net>
 */
class PHP_CodeSniffer_Standards_TYPO3_TYPO3CodingStandard extends PHP_CodeSniffer_Standards_CodingStandard {
	public function getIncludedSniffs() {
		return array('Generic/Sniffs/Files/LineEndingsSniff.php', 'Generic/Sniffs/PHP/DisallowShortOpenTagSniff.php',);
	}
} //end class

?>