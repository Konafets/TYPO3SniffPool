<?php
/**
 * Unit test class for NoAuthorAnnotationInFunctionDocCommentSniff.
 *
 * PHP version 5
 * TYPO3 version 4
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   SVN: $Id$
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
/**
 * Unit test class for NoAuthorAnnotationInFunctionDocCommentSniff.
 *
 * @category  Commenting
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class TYPO3SniffPool_Tests_Commenting_NoAuthorAnnotationInFunctionDocCommentUnitTest extends AbstractSniffUnitTest
{


    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getErrorList()
    {
        return array(
                19 => 1,
                42 => 1,
               );

    }//end getErrorList()


    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getWarningList()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<') === true) {
            return array(
                    60 => 1,
                    67 => 1,
                   );
        } else {
            return array(
                    60 => 1,
                    67 => 1,
                    74 => 1,
                   );
        }

    }//end getWarningList()


}//end class
