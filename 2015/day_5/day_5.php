<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$arr = explode("\n", $input);

$nice = 0;

foreach ($arr as $string) {
    preg_match_all('/[aeiou]/', $string, $matches);
    if (count($matches[0]) < 3) {
        continue;
    }

    if (!preg_match_all('/(\w)\1+/', $string)) {
        continue;
    }

    if (preg_match_all('/(ab)|(cd)|(pq)|(xy)/', $string)) {
        continue;
    }

    $nice++;
}

echo $nice . PHP_EOL;

$nice = 0;

foreach ($arr as $string) {
    if (!preg_match_all('/(..).*\\1/', $string)) {
        continue;
    }

    if (!preg_match_all('/(.).\\1/', $string)) {
        continue;
    }

    $nice++;
}

echo $nice . PHP_EOL;
