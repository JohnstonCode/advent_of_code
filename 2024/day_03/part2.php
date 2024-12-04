<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$matches = [];

preg_match_all('/(mul\(\d{1,3}\,\d{1,3}\)|don\'t\(\)|do\(\))/', $rawInput, $matches);

$result = 0;
$do = true;

foreach ($matches[0] as $match) {
    if ($match === 'do()') {
        $do = true;

        continue;
    }

    if ($match === "don't()") {
        $do = false;

        continue;
    }

    if ($do === false) {
        continue;
    }

    $parts = explode(',', $match);
    $a = preg_replace('/[^0-9]/', '', $parts[0]);
    $b = preg_replace('/[^0-9]/', '', $parts[1]);

    $result += $a * $b;
}

var_dump($result);
