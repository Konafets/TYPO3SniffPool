<?php
// T_EQUAL
$foo = $bar;
$foo=$bar;
$foo= $bar;
$foo =$bar;

// T_PLUS_EQUAL
$foo += $bar;
$foo+=$bar;
$foo+= $bar;
$foo +=$bar;

// T_MINUS_EQUAL
$foo -= $bar;
$foo-=$bar;
$foo-= $bar;
$foo -=$bar;

// T_CONCAT_EQUAL
$foo .= $bar;
$foo.=$bar;
$foo.= $bar;
$foo .=$bar;

// T_AND_EQUAL
$foo &= $bar;
$foo&=$bar;
$foo&= $bar;
$foo &=$bar;

// T_MOD_EQUAL
$foo %= $bar;
$foo%=$bar;
$foo%= $bar;
$foo %=$bar;

// T_MUL_EQUAL
$foo *= $bar;
$foo*=$bar;
$foo*= $bar;
$foo *=$bar;

// T_DIV_EQUAL
$foo /= $bar;
$foo/=$bar;
$foo/= $bar;
$foo /=$bar;

// T_IS_EQUAL
$foo = ($bar == $baz);
$foo = ($bar==$baz);
$foo = ($bar== $baz);
$foo = ($bar ==$baz);

// T_IS_IDENTICAL
$foo = ($bar === $baz);
$foo = ($bar===$baz);
$foo = ($bar=== $baz);
$foo = ($bar ===$baz);

// T_IS_NOT_EQUAL
$foo = ($bar != $baz);
$foo = ($bar!=$baz);
$foo = ($bar!= $baz);
$foo = ($bar !=$baz);

// T_IS_IDENTICAL
$foo = ($bar !== $baz);
$foo = ($bar!==$baz);
$foo = ($bar!== $baz);
$foo = ($bar !==$baz);

// T_LESS_THAN
$foo = ($bar < $baz);
$foo = ($bar<$baz);
$foo = ($bar< $baz);
$foo = ($bar <$baz);

// T_GREATER_THAN
$foo = ($bar > $baz);
$foo = ($bar>$baz);
$foo = ($bar> $baz);
$foo = ($bar >$baz);

// T_IS_SMALLER_OR_EQUAL
$foo = ($bar <= $baz);
$foo = ($bar<=$baz);
$foo = ($bar<= $baz);
$foo = ($bar <=$baz);

// T_IS_GREATER_OR_EQUAL
$foo = ($bar >= $baz);
$foo = ($bar>=$baz);
$foo = ($bar>= $baz);
$foo = ($bar >=$baz);

// T_PLUS
$foo = 1 + 2;
$foo = 1+2;
$foo = 1+ 2;
$foo = 1 +2;

// T_MINUS
$foo = 1 - 2;
$foo = 1-2;
$foo = 1- 2;
$foo = 1 -2;

// T_MULTIPLY
$foo = 1 * 2;
$foo = 1*2;
$foo = 1* 2;
$foo = 1 *2;

// T_DIVIDE
$foo = 1 / 2;
$foo = 1/2;
$foo = 1/ 2;
$foo = 1 /2;

// T_MODULUS
$foo = 1 % 2;
$foo = 1%2;
$foo = 1% 2;
$foo = 1 %2;

$foo = (1 +2)*(5% 3);
$foo = (1+2) *(5 %3);
$foo = (1 + 2)*(5 % 3);
$foo = (1+2) * (5%3);

// T_XOR_EQUAL
$foo ^= $bar;
$foo^=$bar;
$foo^= $bar;
$foo ^=$bar;

// T_DOUBLE_ARROW
$foo = array('foo' => 'bar');
$foo = array('foo'=>'bar');
$foo = array('foo'=> 'bar');
$foo = array('foo' =>'bar');

foo::bar($baz,-10);
foo::bar($baz, -10);
foo::bar(-10, $baz);
foo::bar(- 10, $baz);
foo::bar(-10);

$foo = $bar * -1;
$foo = $bar * - 1;
$foo = $bar *-1;

function foo($bar){
	return -5;
}
?>