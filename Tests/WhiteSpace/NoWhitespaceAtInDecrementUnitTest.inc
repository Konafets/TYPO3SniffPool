<?php
// Prefix ++
$foo = ++$i;
$foo = ++ $i;

// Postfix ++
$foo = $i++;
$foo = $i ++;

// Prefix --
$foo = --$i;
$foo = -- $i;

// Postfix --
$foo = $i--;
$foo = $i --;

for($i = 0; $i < 10; $i++) {

}

for($i = 0; $i < 10; $i ++) {

}

for($i = 0; $i < 10; $i--) {

}

for($i = 0; $i < 10; $i --) {

}

for($i = 0; $i < 10; ++$i) {

}

for($i = 0; $i < 10; ++ $i) {

}

for($i = 0; $i < 10; --$i) {

}

for($i = 0; $i < 10; -- $i) {

}

$i++;
$i ++;
++$i;
++ $i;
$i--;
$i --;
--$i;
-- $i;
