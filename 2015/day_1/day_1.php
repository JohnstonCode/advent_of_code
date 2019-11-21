<?php

$text = file_get_contents(__DIR__ . '/input.txt');
$t = str_split($text);
$v = array_count_values($t);

echo $v["("] - $v[")"] . PHP_EOL;

$floor = 0;

for ($i = 1; $i <= count($t); $i++) {
    $floor += $t[$i - 1] === '(' ? 1 : -1;
    
    if ($floor < 0) {
        break;
    }
}

echo $i . PHP_EOL;
