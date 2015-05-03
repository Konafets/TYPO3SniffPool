<?php
/**
 * TYPO3_Sniffs_NamingConventions_ValidVariableNameSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  NamingConventions
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Stefano Kowalke, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
if (class_exists('PHP_CodeSniffer_Standards_AbstractVariableSniff', true) === false) {
    $error = 'Class PHP_CodeSniffer_Standards_AbstractVariableSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Checks the naming of member variables.
 * All identifiers must use camelCase and start with a lower case letter.
 * Underscore characters are not allowed.
 *
 * @category  NamingConventions
 * @package   TYPO3_PHPCS_Pool
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Stefano Kowalke, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Sniffs_NamingConventions_ValidVariableNameSniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{
    /**
     * Contains built-in TYPO3 variables which we don't check
     *
     * @var array $allowedTypo3InbuiltVariableNames
     */
    protected $allowedTypo3InbuiltVariableNames = array(
                                                   'GLOBALS',
                                                   'TYPO3_CONF_VARS',
                                                   'TYPO3_LOADED_EXT',
                                                   'TYPO3_DB',
                                                   'EXEC_TIME',
                                                   'SIM_EXEC_TIME',
                                                   'TYPO_VERSION',
                                                   'CLIENT',
                                                   'PARSETIME_START',
                                                   'PAGES_TYPES',
                                                   'ICON_TYPES',
                                                   'LANG_GENERAL_LABELS',
                                                   'TCA',
                                                   'TBE_MODULES',
                                                   'TBE_STYLES',
                                                   'T3_SERVICES',
                                                   'T3_VAR',
                                                   'FILEICONS',
                                                   'WEBMOUNTS',
                                                   'FILEMOUNTS',
                                                   'BE_USER',
                                                   'TBE_MODULES_EXT',
                                                   'TCA_DESCR',
                                                   '_EXTKEY',
                                                   'EM_CONF',
                                                   'LANG',
                                                   'BACK_PATH',
                                                   '_REQUEST',
                                                   '_SERVER',
                                                   '_REQUEST',
                                                   '_COOKIE',
                                                   '_FILES',
                                                   'MCONF',
                                                   'MLANG',
                                                   'SOBE',
                                                  );


    /**
     * Processes class member variables.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $memberProps = $phpcsFile->getMemberProperties($stackPtr);
        if (empty($memberProps) === true) {
            return;
        }

        $this->processVariableNameCheck($phpcsFile, $stackPtr, 'member ');

    }//end processMemberVar()


    /**
     * Processes normal variables.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $this->processVariableNameCheck($phpcsFile, $stackPtr);

    }//end processVariable()


    /**
     * Processes variables in double quoted strings.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // We don't care about variables in strings.
        return;

    }//end processVariableInString()


    /**
     * Proceed the whole variable name check.
     * Checks if the variable name has underscores or is written in lowerCamelCase.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     * @param string               $scope     The variable scope. For example "member" if variable is a class property.
     *
     * @return void
     */
    protected function processVariableNameCheck(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $scope = '')
    {
        $tokens       = $phpcsFile->getTokens();
        $variableName = ltrim($tokens[$stackPtr]['content'], '$');
            // There are some historic builtin TYPO3 vars we don't care here.
            // if we found such vars, we leave the sniff here.
        if (in_array($variableName, $this->allowedTypo3InbuiltVariableNames) === true) {
            return;
        }

        $hasUnderscores = stripos($variableName, '_');

        // Check if the variable is named "$_" and is the value variable in a foreach statement
        // foreach ($variable as $key => $_) { ...
        // Because if only a key is needed in a foreach loop, the cgl says that the developer
        // has to rename the foreach value variable $_.
        if ($variableName === '_'
            && $this->isVariableValuePartInForEach($phpcsFile, $stackPtr) === true
        ) {
            return;
        }

        $isLowerCamelCase = PHP_CodeSniffer::isCamelCaps($variableName, false, true, true);
        if ($hasUnderscores !== false) {
            $messageData = array(
                            $scope,
                            $variableName,
                           );
            $error       = 'Underscores are not allowed in the %s variable name "$%s".';

            switch($variableName) {
            case '_POST':
            case '_GET':
                $messageData = array(
                                $variableName,
                                $variableName,
                               );
                $error       = 'Direct access to "$%s" is not allowed; Please use GeneralUtility::%s or GeneralUtility::_GP instead';
                break;
            default:
                $messageData[] = $this->buildExampleVariableName($variableName);
                $error        .= 'Use lowerCamelCase for identifier instead e.g. "$%s"';
            }

            $phpcsFile->addError($error, $stackPtr, 'VariableNameHasUnderscoresNotLowerCamelCased', $messageData);
        } else if ($isLowerCamelCase === false) {
            $pattern = '/([A-Z]{1,}(?=[A-Z]?|[0-9]))/';
            $variableNameLowerCamelCased = preg_replace_callback(
                $pattern,
                function ($m) {
                    return ucfirst(strtolower($m[1]));
                },
                $variableName
            );

            $messageData = array(
                            ucfirst($scope),
                            lcfirst($variableNameLowerCamelCased),
                            $variableName,
                           );
            $error       = '%s variable name must be lowerCamelCase; expect "$%s" but found "$%s"';
            $phpcsFile->addError($error, $stackPtr, 'VariableIsNotLowerCamelCased', $messageData);
        }//end if

    }//end processVariableNameCheck()


    /**
     * Checks if a variable name is named $_ and is located in a foreach loop.
     * If this is the case, the variable name $_ is valid.
     *
     * This kind of variable name is valid if this variable is
     * a) used as value part in a foreach loop
     * b) and not used in foreach body
     * But this case is checked by
     * TYPO3SniffPoo.ControlStructures.UnusedVariableInForEachLoop
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isVariableValuePartInForEach(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $result = false;
        $tokens = $phpcsFile->getTokens();

        // If we got a variable named $_ and it is not located in a foreach loop
        // There are no parenthesis. Exit here.
        if (isset($tokens[$stackPtr]['nested_parenthesis']) === false) {
            return $result;
        }

        // Get the tokens of the normal parenthesis of a foreach statement
        // foreach *(* $variable as $key => $_ *)* {.
        $parenthesisStart = key($tokens[$stackPtr]['nested_parenthesis']);

        // Look for the foreach token.
        $forEachSearch = $phpcsFile->findPrevious(T_FOREACH, $parenthesisStart, null, false, null, true);
        if ($forEachSearch !== false) {
            $result = true;
        }

        return $result;

    }//end isVariableValuePartInForEach()


    /**
     * Returns a modified variable name.
     * e.g. $my_small_variable => $mySmallVariable
     *
     * @param string $variableName Variable name
     *
     * @return string
     */
    protected function buildExampleVariableName($variableName)
    {
        $newName   = '';
        $nameParts = $this->trimExplode('_', $variableName, true);
        $newName   = $this->strToLowerStringIfNecessary(array_shift($nameParts));
        foreach ($nameParts as $part) {
            $newName .= ucfirst(strtolower($part));
        }

        return $newName;

    }//end buildExampleVariableName()


    /**
     * If the incomming $namePart is not camel cased, the string will be lowercased.
     *
     * @param string $namePart Part of a variable name (normal string)
     *
     * @return string
     */
    protected function strToLowerStringIfNecessary($namePart)
    {
        if (PHP_CodeSniffer::isCamelCaps($namePart, false, true, true) === false) {
            $namePart = strtolower($namePart);
        }

        return $namePart;

    }//end strToLowerStringIfNecessary()


    /**
     * The explode() function with trim() for every element.
     *
     * @param string $delim             The boundary string.
     * @param string $string            The input string
     * @param bool   $removeEmptyValues true if empty values should be removed,
     *                                  false otherwise
     * @param int    $limit             Limit of elements which will be returned
     *
     * @return array
     */
    protected function trimExplode($delim, $string, $removeEmptyValues = false, $limit = 0)
    {
        $explodedValues = explode($delim, $string);

        $result = array_map('trim', $explodedValues);

        if ($removeEmptyValues === true) {
            $temp = array();
            foreach ($result as $value) {
                if ($value !== '') {
                    $temp[] = $value;
                }
            }

            $result = $temp;
        }

        if ($limit !== 0) {
            if ($limit < 0) {
                $result = array_slice($result, 0, $limit);
            } else if (count($result) > $limit) {
                $lastElements = array_slice($result, ($limit - 1));
                $result       = array_slice($result, 0, ($limit - 1));
                $result[]     = implode($delim, $lastElements);
            }
        }

        return $result;

    }//end trimExplode()


}//end class
