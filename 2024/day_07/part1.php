<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$calibrations = [];

foreach (explode("\n", $rawInput) as $line) {
    [$result, $nums] = explode(": ", $line);
    $calibrations[] = [(int) $result, ...array_map(fn ($num) => (int) $num, explode(' ', $nums))];
}

function solve(int $total, array $nums): bool
{
    if (count($nums) === 1) {
        return $nums[0] === $total;
    }

    $a = array_shift($nums);
    $b = array_shift($nums);
    $add = $a + $b;
    $mul = $a * $b;

    return solve($total, [$add, ...$nums]) || solve($total, [$mul, ...$nums]);
}

$total = 0;

foreach ($calibrations as $calibration) {
    $result = array_shift($calibration);
    if (solve($result, $calibration)) {
        $total += $result;
    }
}


var_dump($total);
