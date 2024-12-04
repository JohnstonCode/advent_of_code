<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$matches = [];

preg_match_all('/mul\(\d{1,3}\,\d{1,3}\)/', $rawInput, $matches);

$result = 0;

foreach ($matches[0] as $match) {
    $parts = explode(',', $match);
    $a = preg_replace('/[^0-9]/', '', $parts[0]);
    $b = preg_replace('/[^0-9]/', '', $parts[1]);

    $result += $a * $b;
}

var_dump($result);

