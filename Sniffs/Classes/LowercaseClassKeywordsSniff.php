<?php
/**
 * Ensures all class keywords are lowercase.
 *
 * PHP version 5
 *
 * @category  Classes
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Andy Grunwald
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\Classes;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\LowercaseClassKeywordsSniff
    as LowercaseClassKeywords;

/**
 * Ensures all class keywords are lowercase.
 *
 * @category  Classes
 * @package   TYPO3SniffPool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @author    Stefano Kowalke <blueduck@mailbox.org>
 * @copyright 2010 Andy Grunwald
 * @copyright 2015 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class LowercaseClassKeywordsSniff extends LowercaseClassKeywords
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_CLASS,
                T_INTERFACE,
                T_EXTENDS,
                T_IMPLEMENTS,
                T_ABSTRACT,
                T_FINAL,
                T_TRAIT,
                T_VAR,
                T_CONST,
                T_PRIVATE,
                T_PUBLIC,
                T_PROTECTED,
               );

    }//end register()


}//end class
