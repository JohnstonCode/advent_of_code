<?php

$text = file_get_contents(__DIR__ . '/input.txt');
$t = str_split($text);
$v = array_count_values($t);

echo $v["("] - $v[")"] . PHP_EOL;

$floor = 0;
$i = 1;

foreach ($t as $paren) {
    if ($paren === '(') {
        $floor++;
    } elseif ($paren === ')') {
        $floor--;
    }

    if ($floor === -1) {
        break;
    }

    $i++;
}

echo $i . PHP_EOL;
