The TYPO3 Sniff Pool
====================

[![TravisCI Build Status](https://travis-ci.org/typo3-ci/TYPO3SniffPool.svg?branch=develop)](https://travis-ci.org/typo3-ci/TYPO3SniffPool)

Every big project has its own coding standards for syntax and formatting (Coding Guidelines / CGL). For example [Zend Framework](http://framework.zend.com/manual/en/coding-standard.html), [PEAR](http://pear.php.net/manual/en/standards.php), [Drupal](http://drupal.org/coding-standards), [Symfony2](http://symfony.com/doc/current/contributing/code/standards.html) and so on. [TYPO3 CMS](http://docs.typo3.org/typo3cms/CodingGuidelinesReference/) and [Flow / Neos](http://docs.typo3.org/flow/TYPO3FlowDocumentation/TheDefinitiveGuide/PartV/CodingGuideLines/Index.html) as well.

With the [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) it is possible to detect violations from a defined set of rules (called sniffs) for PHP, JavaScript and CSS files.

This repository contains self developed sniffs based on the [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) for the [TYPO3 Project](http://typo3.org/) to check coding guideline violation.
We call this repository the _TYPO3 Sniff Pool_, because it contains all sniffs we use in the seperate coding standards like [TYPO3Flow](https://github.com/typo3-ci/TYPO3Flow) and [TYPO3CMS](https://github.com/typo3-ci/TYPO3CMS).

How to install
==============

Since this standard is a dependency of [TYPO3CMS](https://github.com/typo3-ci/TYPO3CMS) and [TYPO3Flow](https://github.com/typo3-ci/TYPO3Flow) standard, you don't need to install this standard directly. It will automatically installed when you install one of the mentioned standards. 

How to use
==========

Since it is an own standard it is possible to call it. 

**This is not recommended! It will raise weird errors.**

We provide separate standards who match the coding guidelines of [TYPO3CMS](https://github.com/typo3-ci/TYPO3CMS) and [TYPO3Flow](https://github.com/typo3-ci/TYPO3Flow).

Documentation
=============
We collect the complete documentation of this repository, the self developed sniffs, how to install our custom standards, how to contribute to this repository and where you can find further information in our [wiki](https://github.com/typo3-ci/TYPO3SniffPool/wiki).

Contribution
============
**Contribution is already welcome!**

We collected a bunch of information how to contribute to this project in our [wiki](https://github.com/typo3-ci/TYPO3SniffPool/wiki#contribute). There you can find information about the used [branching model](https://github.com/typo3-ci/TYPO3SniffPool/wiki/Branching-model), how to execute [unit tests](https://github.com/typo3-ci/TYPO3SniffPool/wiki/Unit-tests) or about [TravisCI](https://github.com/typo3-ci/TYPO3SniffPool/wiki/TravisCI).