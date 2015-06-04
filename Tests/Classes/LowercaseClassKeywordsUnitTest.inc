<?php
CLASS lowercaseClassKeywordsBase {
	PUBLIC $foo = null;
	PROTECTED $bar = null;
	PRIVATE $baz = null;
	VAR $fooBar = null;
	CONST barBaz = null;
}

class lowercaseClassKeywordsChild EXTENDS lowercaseClassKeywordsBase {
	
}

INTERFACE lowercaseClassKeywordsInterface {
	
}

class lowercaseClassKeywordsImplements IMPLEMENTS lowercaseClassKeywords {
	
}

ABSTRACT class lowercaseClassKeywordsAbstract {
	
}

FINAL class lowercaseClassKeywordsFinal {
	
}

Trait MyTrait {}
