<?php
while ($result === false) {
    // Some code
}

while ($result) {
    // Some code
}

while ($result < 5) {
    // Some code
}

while ($row = $GLOBALS['TYPO3']->sql_fetch_assoc($res)) {
    // Some code
}

while (($row = $GLOBALS['TYPO3']->sql_fetch_assoc($res))) {
    // Some code
}

do {
    // Some code
} while ($row = $GLOBALS['TYPO3']->sql_fetch_assoc($res));

do {
    // Some code
} while (($row = $GLOBALS['TYPO3']->sql_fetch_assoc($res)));

if ($fields = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
    // Do something
}

if (($fields = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
    // Do something
}

if (FALSE !== ($fields = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
    // Do something
}
