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
 * TYPO3_Sniffs_PHP_XClassSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  PHP
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright Copyright (c) 2010, Stefano Kowalke
 * @license	  http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   SVN: $ID$
 * @link      http://pear.typo3.org
 */
/**
 * Checks for a XClass line at the end of the class
 *
 * @category  PHP
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright Copyright (c) 2010, Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      http://pear.typo3.org
 */
class TYPO3_Sniffs_PHP_XClassSniff implements PHP_CodeSniffer_Sniff {
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register() {
        return array(T_CLASS);
    } //end register()
    
    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $filePath = $phpcsFile->getFilename();
            // XClass declarations are only needed in extensions, so we have to check
            // if the current file is part of a sys- or user extension
        if (!preg_match('/sysext|ext/', $filePath)) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $closingBracket = $tokens[$stackPtr]['scope_closer'];



        if ($closingBracket === NULL) {
            // Possible inline structure. Other tests will handle it.
            return;
        }

            // No XClass definition required if classname starts with 'ux_'
        $classToken = $phpcsFile->findNext(array(T_STRING), $stackPtr);
        $className = $tokens[$classToken]['content'];
        if (preg_match('/^ux_/', $className) === 1) {
            return;
        }

        $XClass = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $closingBracket, NULL, FALSE, "'XCLASS'");

        

//		echo "\n" . 'ClassName: ' . preg_match('/^ux_/', $className) . "\n";
//		echo "\n" . 'FilenNameEscaped: ' . $fileNameEscaped . "\n";

            // Do a first simple check for the string 'XCLASS' before we digg deeper
        if (($tokens[$XClass]['content'] !== "'XCLASS'")) {
            $error = 'The XCLASS declaration must follow the PHP class, but I didn\'t found one or its wrong.';
            $code  = 'WrongOrNotFound';
        } else {
            $reg = array();

            $relExtPath = stristr($filePath, 'ext');
            echo $relExtPath;
            $extName = $this->getExtensionNamefromPath($relExtPath);
            $pathToExtension = $this->getPathToExtensionRoot($filePath, $extName);
            #echo $extName;
            $content = file_get_contents($filePath);
            $stackPtr = $XClass;

            /* Check for proper XCLASS declaration */
            /* The following code was inspired by the XClass check from typo3/sysext/em/mod1/class.em_index.php */

                // Match $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS'] with single or doublequotes
            $XclassSearch = '\$TYPO3_CONF_VARS\[TYPO3_MODE\]\[[\'"]XCLASS[\'"]\]';
            $XclassParts = preg_split('/if \(defined\([\'"]TYPO3_MODE[\'"]\) && ' . $XclassSearch . '/', $content, 2);
            
            if (count($XclassParts) !== 2) {
                    // Match $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS'] with single or doublequotes
                $XclassSearch = '\$GLOBALS\[[\'"]TYPO3_CONF_VARS[\'"]\]\[TYPO3_MODE\]\[[\'"]XCLASS[\'"]\]';
                $XclassParts = preg_split('/if \(defined\([\'"]TYPO3_MODE[\'"]\) && ' . $XclassSearch . '/', $content, 2);
                $error = 'The XCLASS declaration must follow the PHP class, but I didn\'t found one or its wrong.';
                $code  = 'NotFoundy';
            }
            if (count($XclassParts) == 2)	{
                unset($reg);
                preg_match('/^\[[\'"]([[:alnum:]_\/\.]*)[\'"]\]/', $XclassParts[1], $reg);
                if ($reg[1]) {
                    if (!strcmp($reg[1], $relExtPath))	{
                        if (preg_match('/_once[[:space:]]*\(' . $XclassSearch . '\[[\'"]' . preg_quote($relExtPath, '/') . '[\'"]\]\);/', $XclassParts[1])) {
                                // XClass declaration seems to be OK. So we have to leave here.
                            return;
                        } else {
                            $includeLineStart = strpos($XclassParts[1], 'include');
                            $includeLineEnd = strpos($XclassParts[1], ';');
                            $includeLine = substr($XclassParts[1], $includeLineStart, ($includeLineEnd + 1) - $includeLineStart);
                            $error = 'Couldn\'t find a correct include_once statement for XCLASS; expect "include_once($TYPO3_CONF_VARS[TYPO3_MODE][\'XCLASS\'][\'' . $relExtPath . '\']);" but found "' . $includeLine . '"';
                            $code  = 'NoInclude';
                        }
                    } else {
                        $error = sprintf('Don\'t found a proper XCLASS filename-key; expect "%s", but found "%s"',
                            $relExtPath, $reg[1]);
                        $code = 'WrongFilenameKey';
                    }
                } else {
                    $error = sprintf('No XCLASS filename-key found in file "%s".', $fileName);
                    $code  = 'NoFilenameKeyFound';
                }
            } else {
                $error = 'The XCLASS declaration must follow the PHP class, but I didn\'t found one or its wrong.';
                $code  = 'WrongOrNotFound';
            }
        }
        
        $phpcsFile->addError($error, $stackPtr, $code);
    } //end process()

    /**
     * Extract the extension name form a given path
     *
     * @param string $path The complete path; starting from ext/
     * @return mixed $extName The name of the extension or FALSE if no found
     */
    protected function getExtensionNamefromPath($path) {
        $extName = explode('/', $path);
        if (isset($extName) && $extName[0] === 'ext') {
            $extName = $extName[1];
        } else {
            $extName = FALSE;
        }
        
        return $extName;
    }

    protected function getPathToExtensionRoot($path, $extname) {
        $pathLenght = strlen($path);
        $extLenght  = strlen($extname);
        $extNamePos = strpos($path, $extname);

        $absPathToExtension = substr($path, 0, $extNamePos + $extLenght);
        echo '$pathLenght: ' . $pathLenght . "\n";
        echo '$extLenght: ' . $extLenght . "\n";
        echo '$absPathToExtension: ' . $absPathToExtension . "\n";
        #echo $extNamePos;

        return $absPathToExtension;
    }
} //end class

?>