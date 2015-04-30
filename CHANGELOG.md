# Change Log

## [Unreleased](https://github.com/typo3-ci/TYPO3SniffPool/tree/HEAD)

[Full Changelog](https://github.com/typo3-ci/TYPO3SniffPool/compare/1.0.1...HEAD)

**Implemented enhancements:**

- ValidCommentLineLengthSniff override maxCommentLength [\#59](https://github.com/typo3-ci/TYPO3SniffPool/issues/59)

- \[CLEANUP\] Format the sniffs to PHPCS coding guidelines. [\#57](https://github.com/typo3-ci/TYPO3SniffPool/issues/57)

- \[TASK\] Refactor TYPO3SniffPool\_Sniffs\_ControlStructures\_SwitchDeclarationSniff [\#55](https://github.com/typo3-ci/TYPO3SniffPool/issues/55)

- Replace old class names like t3lib\_div [\#23](https://github.com/typo3-ci/TYPO3SniffPool/issues/23)

**Fixed bugs:**

- False positive for @author tags in classes [\#58](https://github.com/typo3-ci/TYPO3SniffPool/issues/58)

- \[BUG\] Redundant check for valid break statements in switch structures [\#56](https://github.com/typo3-ci/TYPO3SniffPool/issues/56)

- \[BUGFIX\] Detection of line start in NoAuthorAnnotationInFunctionDocCommentSniff breaks code fixer [\#52](https://github.com/typo3-ci/TYPO3SniffPool/issues/52)

- PHPStorm integration shows @return void "error" only once per file [\#49](https://github.com/typo3-ci/TYPO3SniffPool/issues/49)

- Negative number parsed as arithemtic operation [\#47](https://github.com/typo3-ci/TYPO3SniffPool/issues/47)

- \[BUG\] Check for default switch case is redunant [\#46](https://github.com/typo3-ci/TYPO3SniffPool/issues/46)

**Closed issues:**

- Checking has been aborted [\#54](https://github.com/typo3-ci/TYPO3SniffPool/issues/54)

- \[TASK\] Check the code style of the sniffs against PHPCS standard on Travis-CI [\#51](https://github.com/typo3-ci/TYPO3SniffPool/issues/51)

- Adjust FunctionDocComment to the new commenting tokenizer [\#38](https://github.com/typo3-ci/TYPO3SniffPool/issues/38)

**Merged pull requests:**

- \[TASK\] Make it possible to override maxCommentLength [\#60](https://github.com/typo3-ci/TYPO3SniffPool/pull/60) ([kj187](https://github.com/kj187))

- \[TASK\] Fixed typo [\#53](https://github.com/typo3-ci/TYPO3SniffPool/pull/53) ([derhansen](https://github.com/derhansen))

- \[BUGFIX\] Fix typo in PHPDoc [\#50](https://github.com/typo3-ci/TYPO3SniffPool/pull/50) ([MattiasNilsson](https://github.com/MattiasNilsson))

## [1.0.1](https://github.com/typo3-ci/TYPO3SniffPool/tree/1.0.1) (2015-01-23)

[Full Changelog](https://github.com/typo3-ci/TYPO3SniffPool/compare/1.0.0...1.0.1)

**Implemented enhancements:**

- Generate / Create documentation of every sniff [\#25](https://github.com/typo3-ci/TYPO3SniffPool/issues/25)

- Adjust TYPO3SniffPool.Commenting.ClassDocComment to the new header format. [\#18](https://github.com/typo3-ci/TYPO3SniffPool/issues/18)

**Fixed bugs:**

- Rename WhiteSpace/AsteriksWhitespacesSniff  [\#44](https://github.com/typo3-ci/TYPO3SniffPool/issues/44)

**Closed issues:**

- \[TASK\] Remove WhiteSpace.AsteriksWhitespacesSniff [\#48](https://github.com/typo3-ci/TYPO3SniffPool/issues/48)

- Remove the PEAR installation from README [\#45](https://github.com/typo3-ci/TYPO3SniffPool/issues/45)

- Update composer file to the github url and wiki [\#43](https://github.com/typo3-ci/TYPO3SniffPool/issues/43)

- Adjust DisallowSpaceIndentSniff to the new commenting tokenizer [\#42](https://github.com/typo3-ci/TYPO3SniffPool/issues/42)

- Adjust AsteriksWhitespace to the new commenting tokenizer [\#41](https://github.com/typo3-ci/TYPO3SniffPool/issues/41)

- Adjust AlwaysReturnSniff to the new comment tokenizer [\#40](https://github.com/typo3-ci/TYPO3SniffPool/issues/40)

- Adjust ValidCommentLineLength to the new commenting tokenizer [\#39](https://github.com/typo3-ci/TYPO3SniffPool/issues/39)

- \[TASK\] Adjust the NoAuthorAnnotationInFunctionDocCommentSniff to the new commenting tokenizer [\#37](https://github.com/typo3-ci/TYPO3SniffPool/issues/37)

## [1.0.0](https://github.com/typo3-ci/TYPO3SniffPool/tree/1.0.0) (2014-09-03)

[Full Changelog](https://github.com/typo3-ci/TYPO3SniffPool/compare/1.0.0-alpha...1.0.0)

**Implemented enhancements:**

- Migrate the wiki from Forge to Github [\#22](https://github.com/typo3-ci/TYPO3SniffPool/issues/22)

- Rework .travis.yml to run on hhvm as well [\#21](https://github.com/typo3-ci/TYPO3SniffPool/issues/21)

- Remove references to PEAR [\#20](https://github.com/typo3-ci/TYPO3SniffPool/issues/20)

- Create a chapter about "Contribution" [\#19](https://github.com/typo3-ci/TYPO3SniffPool/issues/19)

- ValidVariableNameSniff.php raises Deprecation Error in PHP 5.5 [\#15](https://github.com/typo3-ci/TYPO3SniffPool/issues/15)

- Improve the check for non class files [\#13](https://github.com/typo3-ci/TYPO3SniffPool/issues/13)

- Adjust the @link annotation to github in every file [\#9](https://github.com/typo3-ci/TYPO3SniffPool/issues/9)

- Add a sniff to check for the correct file extension [\#7](https://github.com/typo3-ci/TYPO3SniffPool/issues/7)

- Add the class keyword "traits" to the LowercaseClassKeywordSniff [\#6](https://github.com/typo3-ci/TYPO3SniffPool/issues/6)

**Fixed bugs:**

- AlwaysReturnSniff does not respect @return void of outer function with anonymous function [\#26](https://github.com/typo3-ci/TYPO3SniffPool/issues/26)

- At some systems FilenameSniff returns a number instead of the filename [\#12](https://github.com/typo3-ci/TYPO3SniffPool/issues/12)

- FilenameSniff crashes if he encounter a file w/o a class [\#11](https://github.com/typo3-ci/TYPO3SniffPool/issues/11)

**Closed issues:**

- Add documentation for AssignmentArithmeticAndComparisonSpaceSniff [\#5](https://github.com/typo3-ci/TYPO3SniffPool/issues/5)

**Merged pull requests:**

- waffle.io Badge [\#14](https://github.com/typo3-ci/TYPO3SniffPool/pull/14) ([waffleio](https://github.com/waffleio))

- Php55 pre replace callback [\#16](https://github.com/typo3-ci/TYPO3SniffPool/pull/16) ([Konafets](https://github.com/Konafets))

- Develop [\#10](https://github.com/typo3-ci/TYPO3SniffPool/pull/10) ([Konafets](https://github.com/Konafets))

## [1.0.0-alpha](https://github.com/typo3-ci/TYPO3SniffPool/tree/1.0.0-alpha) (2013-11-08)

[Full Changelog](https://github.com/typo3-ci/TYPO3SniffPool/compare/0.0.6...1.0.0-alpha)

**Merged pull requests:**

- Make ruleset installable into composer project by using simplyadmire/composer-plugins [\#4](https://github.com/typo3-ci/TYPO3SniffPool/pull/4) ([radmiraal](https://github.com/radmiraal))

- Make sniff pool installable using composer [\#3](https://github.com/typo3-ci/TYPO3SniffPool/pull/3) ([kdambekalns](https://github.com/kdambekalns))

## [0.0.6](https://github.com/typo3-ci/TYPO3SniffPool/tree/0.0.6) (2013-05-04)

[Full Changelog](https://github.com/typo3-ci/TYPO3SniffPool/compare/0.0.5...0.0.6)

## [0.0.5](https://github.com/typo3-ci/TYPO3SniffPool/tree/0.0.5) (2013-04-30)

[Full Changelog](https://github.com/typo3-ci/TYPO3SniffPool/compare/0.0.4...0.0.5)

**Merged pull requests:**

- Rename the name of this standard [\#2](https://github.com/typo3-ci/TYPO3SniffPool/pull/2) ([Konafets](https://github.com/Konafets))

- \[FEATURE\] Add support for travisci [\#1](https://github.com/typo3-ci/TYPO3SniffPool/pull/1) ([andygrunwald](https://github.com/andygrunwald))

## [0.0.4](https://github.com/typo3-ci/TYPO3SniffPool/tree/0.0.4) (2012-09-21)

[Full Changelog](https://github.com/typo3-ci/TYPO3SniffPool/compare/0.0.3...0.0.4)

## [0.0.3](https://github.com/typo3-ci/TYPO3SniffPool/tree/0.0.3) (2011-01-14)

[Full Changelog](https://github.com/typo3-ci/TYPO3SniffPool/compare/0.0.2...0.0.3)

## [0.0.2](https://github.com/typo3-ci/TYPO3SniffPool/tree/0.0.2) (2010-12-14)



\* *This Change Log was automatically generated by [github_changelog_generator](https://github.com/skywinder/Github-Changelog-Generator)*