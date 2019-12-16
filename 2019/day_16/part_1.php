<?php

$input = trim(file_get_contents(__DIR__ . '/input.txt'));
$length = strlen($input);
$copy = str_split($input);
$pattern = [0, 1, 0, -1];

for ($x = 0; $x < 100; $x++) {
    $output = [];

    for ($i = $length; $i >= 1; $i--) {
        $sum = 0;
        for ($j = $length - 1, $to = $i - 1; $j >= $to; $j--) {
            $delta = (int) (($j + 1) / $i) % 4;
            $sum += $copy[$j] * $pattern[$delta];
        }

        $output[] = abs($sum) % 10;
    }

    $copy = array_reverse($output);
}

echo implode('', array_slice($copy, 0, 8)) . PHP_EOL;
