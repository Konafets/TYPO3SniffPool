<?php
/**
 * TYPO3_Sniffs_NamingConventions_ValidFunctionNameSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  NamingConventions
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2013 Stefano Kowalke
 * @copyright 2010 Laura Thewalt, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Checks that the functions named by lowerCamelCase
 *
 * No Underscores are allowed
 * Correct:   function testFunctionName ()
 * Incorrect: function Test_Function_name ()
 *            function TestFunctionname ()
 *
 * @category  NamingConventions
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2013 Stefano Kowalke
 * @copyright 2010 Laura Thewalt, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_NamingConventions_ValidFunctionNameSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of tokenizers this sniff supports
     *
     * @var array
     */
    public $supportedTokenizes = array('PHP');


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens       = $phpcsFile->getTokens();
        $functionName = $phpcsFile->findNext(array(T_STRING), $stackPtr);
        if (($this->isFunctionAMagicFunction($tokens[$functionName]['content']) === true)
            || ($this->isFunctionAPiBaseFunction($tokens[$functionName]['content']) === true)
            || ($this->isFunctionUserFunction($tokens[$functionName]['content']) === true)
        ) {
            return;
        }

        $hasUnderscores   = stripos($tokens[$functionName]['content'], '_');
        $isLowerCamelCase = PHP_CodeSniffer::isCamelCaps(
            $tokens[$functionName]['content'],
            false,
            true,
            true
        );
        $scope            = $this->getCorrectScopeOfToken($tokens, $stackPtr);
        if ($hasUnderscores !== false) {
            $error  = 'Underscores are not allowed in %s names "%s"; ';
            $error .= 'use lowerCamelCase for %s names instead';
            $error .= '';
            $data   = array(
                       $scope,
                       $tokens[$functionName]['content'],
                       $scope,
                      );

            $phpcsFile->addError($error, $stackPtr, 'UnderscoresInFunctionName', $data);
        } else if ($isLowerCamelCase === false) {
            $error = '%s name "%s" must use lowerCamelCase';
            $data  = array(
                      ucfirst($scope),
                      $tokens[$functionName]['content'],
                     );

            $phpcsFile->addError($error, $stackPtr, 'FilenameLowerCase', $data);
        }

    }//end process()


    /**
     * Returns the scope (function|method) of a found function.
     *
     * @param array $tokens   Array with all tokens of the current file
     * @param int   $stackPtr Stack pointer where function was found
     *
     * @return string
     */
    public function getCorrectScopeOfToken(array $tokens, $stackPtr)
    {
        $scope = 'function';
        if (is_array($tokens[$stackPtr]['conditions']) === false) {
            return $scope;
        }

        foreach ($tokens[$stackPtr]['conditions'] as $tokenType) {
            if ($tokenType === T_CLASS) {
                $scope = 'method';
                break;
            }
        }

        return $scope;

    }//end getCorrectScopeOfToken()


    /**
     * Returns TRUE if the called function / method is a magic method of php
     *
     * @param string $name Name of function
     *
     * @return boolean
     * @see    http://php.net/manual/en/language.oop5.magic.php
     */
    public function isFunctionAMagicFunction($name)
    {
        $result = false;
        switch ($name) {
        case '__construct':
        case '__destruct':
        case '__call':
        case '__callStatic':
        case '__get':
        case '__set':
        case '__isset':
        case '__unset':
        case '__sleep':
        case '__wakeup':
        case '__toString':
        case '__invoke':
        case '__set_state':
        case '__clone':
        case '__autoload':
            $result = true;
            break;
        }

        return $result;

    }//end isFunctionAMagicFunction()


    /**
     * Returns TRUE if called function / method is a pi_-based method
     * of TYPO3.
     * If you create a frontend plugin for TYPO3 on piBase and overwrite
     * an existing method from tslib_pibase which starts with "pi_"
     * the codesniffer will mark this as an error.
     *
     * This method contains a full list of methods who starts with "pi_"
     * and are from tslib_pibase (TYPO3 4.5.15 LTS).
     *
     * @param string $name Name of function
     *
     * @return boolean
     */
    protected function isFunctionAPiBaseFunction($name)
    {
        $result = false;

        switch ($name) {
        case 'pi_autoCache':
        case 'pi_classParam':
        case 'pi_exec_query':
        case 'pi_getCategoryTableContents':
        case 'pi_getClassName':
        case 'pi_getEditIcon':
        case 'pi_getEditPanel':
        case 'pi_getFFvalue':
        case 'pi_getFFvalueFromSheetArray':
        case 'pi_getLL':
        case 'pi_getPageLink':
        case 'pi_getPidList':
        case 'pi_getRecord':
        case 'pi_initPIflexForm':
        case 'pi_isOnlyFields':
        case 'pi_linkToPage':
        case 'pi_linkTP':
        case 'pi_linkTP_keepPIvars':
        case 'pi_linkTP_keepPIvars_url':
        case 'pi_list_browseresults':
        case 'pi_list_header':
        case 'pi_list_linkSingle':
        case 'pi_list_makelist':
        case 'pi_list_modeSelector':
        case 'pi_list_query':
        case 'pi_list_row':
        case 'pi_list_searchBox':
        case 'pi_loadLL':
        case 'pi_openAtagHrefInJSwindow':
        case 'pi_prependFieldsWithTable':
        case 'pi_RTEcssText':
        case 'pi_setClassStyle':
        case 'pi_setPiVarDefaults':
        case 'pi_wrapInBaseClass':
            $result = true;
            break;
        }//end switch

        return $result;

    }//end isFunctionAPiBaseFunction()


    /**
     * Returns true if the function is an user function
     *
     * @param string $name The name of the function
     *
     * @return bool
     */
    public function isFunctionUserFunction($name)
    {
        $result = false;

        if (substr_compare($name, 'user_', 0, 5) === 0) {
            $result = true;
        }

        return $result;

    }//end isFunctionUserFunction()


}//end class
