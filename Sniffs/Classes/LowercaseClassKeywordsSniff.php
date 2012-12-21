<?php
/**
 * TYPO3_Sniffs_Classes_LowercaseClassKeywordsSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Classes
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      http://pear.typo3.org
 */
/**
 * Ensures all class keywords are lowercase.
 *
 * @category  Classes
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3SniffPool_Sniffs_Classes_LowercaseClassKeywordsSniff extends Squiz_Sniffs_Classes_LowercaseClassKeywordsSniff
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
                T_VAR,
                T_CONST,
                T_PRIVATE,
                T_PUBLIC,
                T_PROTECTED
                );
    }
}
?>