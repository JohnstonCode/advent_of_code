<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$left = [];
$right = [];

foreach (explode("\n", $rawInput) as $line) {
    $values = array_values(array_filter(explode(' ', $line)));
    $left[] = (int) $values[0];
    $right[] = (int) $values[1];
}

$rightListCount = array_count_values($right);
$totalDistance = 0;

for ($i = 0; $i < count($left); $i++) {
     $num = $left[$i];

    $totalDistance += ($num * ($rightListCount[$num] ?? 0));
}

var_dump($totalDistance);
