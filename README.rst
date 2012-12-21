====================================
The TYPO3 PHP_CodeSniffer Sniff Pool
====================================

Travis Status
=============
..image:: https://travis-ci.org/typo3-ci/TYPO3SniffPool.png?branch=master

Description
===========
This folder we call "the sniff pool" which is the home of all sniffs which we (re)implemented for our Coding Guidelines.

How to use
==========
Technically its possible to use this as standard by PHPCS (phpcs --standard=TYPO3 /path/to/file), because its a own standard.
But this is not recommended because this folder contains ALL sniffs for TYPO3v4, FLOW3, so the result of the codesniffer will be wrong.

Further informations
====================
For further informations please have a look at:
http://forge.typo3.org/projects/team-php_codesniffer/wiki/Using_the_TYPO3_Coding_Standard
