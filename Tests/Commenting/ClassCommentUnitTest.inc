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
// Extra description newlines


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
// Missing newlines before tags


/**
 *
 *
 */
// Empty doc comment

interface No_Comment {}


//
// Sample class comment
//
//
//
interface Invalid_Comment_Style1 {}