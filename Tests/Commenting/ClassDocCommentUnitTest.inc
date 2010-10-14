<?php
class No_Comment {}


//
// Sample class comment
//
//
//
class Invalid_Comment_Style1 {}


/**
 *
 *
 * Sample class comment
 *
 *
 * Long description with extra blank line before and after
 *
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Extra_Description_Newlines {}


/**
 * Sample class comment
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Missing_Newlines_Before_Tags {}


/**
 *
 *
 */
class Empty_Class_Doc {}


/**
 *
 *
 */
interface Empty_Interface_Doc {}
?>