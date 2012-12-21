<?php
// wrong
switch($foo){
    case 1:
        doSomething();
        break;
}

// should be ok
switch($foo){
    case 1:
        doSomething();
        break;
    default:
}

class TYPO3SniffPool_Sniffs_Files_IncludingFileSniffUniTest {

	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$keyword = $tokens[$stackPtr]['content'];
		$tokenCode = $tokens[$stackPtr]['type'];
		switch ($tokenCode) {
			case 'T_INCLUDE_ONCE':
				$error = 'Including files with "' . $keyword . '" is not allowed; ';
				$error .= 'use "require_once" instead';
				$phpcsFile->addError($error, $stackPtr);
				break;
			case 'T_REQUIRE':
				$error = 'Including files with "' . $keyword . '" is not allowed; ';
				$error .= 'use "require_once" instead';
				$phpcsFile->addError($error, $stackPtr);
				break;
			case 'T_INCLUDE':
				$error = 'Including files with "' . $keyword . '" is not allowed; ';
				$error .= 'use "require_once" instead';
				$phpcsFile->addError($error, $stackPtr);
				break;
			default:
		}
	}
}
?>