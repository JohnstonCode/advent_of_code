<?php

$input = file_get_contents(__DIR__ . '/input.txt');

$offset = (int) substr($input, 0, 7);
$input = substr(str_repeat($input, 10000), $offset);
$input = str_split($input);
$length = count($input);
$result = null;

for ($x = 0; $x < 100; $x++) {
    $result = [$length => 0];

    for ($i = $length; $i >= 0; $i--) {
        $result[$i] = (($input[$i] ?? 0) + ($result[$i + 1] ?? 0)) % 10;
    }

    $input = $result;
}

echo implode('', array_reverse(array_slice($input, -8))) . PHP_EOL;
