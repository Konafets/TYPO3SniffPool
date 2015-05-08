<?php
/**
 * Exactly one pair of opening and closing tags are allowed
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   TYPO3SniffPool
 * @author    Julian Kleinhans <kleinhans@bergisch-media.de>
 * @copyright 2010 Julian Kleinhans
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */

namespace TYPO3CI\Standards\TYPO3SniffPool\Sniffs\PHP;

use \PHP_CodeSniffer\Sniffs\Sniff;
use \PHP_CodeSniffer\Files\File;

/**
 * Exactly one pair of opening and closing tags are allowed
 *
 * @category  PHP
 * @package   TYPO3SniffPool
 * @author    Julian Kleinhans <kleinhans@bergisch-media.de>
 * @copyright 2010 Julian Kleinhans
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class DisallowMultiplePHPTagsSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP');


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_OPEN_TAG,
                T_CLOSE_TAG,
               );

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $disallowTag = $phpcsFile->findNext($tokens[$stackPtr]['code'], ($stackPtr + 1));
        if (false !== $disallowTag) {
            $error = 'Exactly one "'.$tokens[$stackPtr]['content'].'" tag is allowed';
            $phpcsFile->addError($error, $disallowTag);
        }

        return;

    }//end process()


}//end class
