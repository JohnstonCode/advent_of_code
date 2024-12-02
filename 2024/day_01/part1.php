<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$left = [];
$right = [];

foreach (explode("\n", $rawInput) as $line) {
    $values = array_values(array_filter(explode(' ', $line)));
    $left[] = (int) $values[0];
    $right[] = (int) $values[1];
}

sort($left, SORT_NUMERIC);
sort($right, SORT_NUMERIC);

$totalDistance = 0;

for ($i = 0; $i < count($left); $i++) {
    $totalDistance += abs($left[$i] - $right[$i]);
}

var_dump($totalDistance);
