<?php

$inputText = trim(file_get_contents(__DIR__ . '/input.txt'));
// [Y, X]
$dirs = [
    //up, left, down right
    [-1, 0],
    [0, 1],
    [1, 0],
    [0, -1],
    // all diags
    [1, 1],
    [-1, 1],
    [-1, -1],
    [1, -1],
];

$schematic = [];
$maxX = 0;
$maxY = 0;
$partNumbers = [];

foreach (explode("\n", $inputText) as $y => $line) {
    $parts = str_split($line);

    foreach ($parts as $x => $item) {
        $schematic["$y:$x"] = $item;

        $maxX = max($maxX, $x);
    }

    $maxY = max($maxY, $y);
}

$toCheck = [];
$gearRatios = [];

for ($y = 0; $y <= $maxY; $y++) {

    $num = '';
    $found = false;
    $gears = [];
    for ($x = 0; $x <= $maxX; $x++) {
        $item = $schematic["$y:$x"];

        if (!is_numeric($item)) {
            if ($found) {
                $partNumbers[] = (int) $num;
            }

            if ($gears) {
                foreach ($gears as $gear) {
                    $gearRatios[$gear] ??= [];
                    $gearRatios[$gear][] = (int) $num;
                }
            }

            $num = '';
            $found = false;
            $gears = [];

            continue;
        }

        $num .= $item;

        foreach ($dirs as $dir) {
            [$yy, $xx] = $dir;

            $newY = $y + $yy;
            $newX = $x + $xx;

            if (isset($schematic["$newY:$newX"]) && !is_numeric($schematic["$newY:$newX"]) && $schematic["$newY:$newX"] !== '.') {
                $found = true;

                if ($schematic["$newY:$newX"] === '*') {
                    $gears[] = "$newY:$newX";
                    $gears = array_unique($gears);
                }
            }
        }
    }

    if ($found) {
        $partNumbers[] = (int) $num;
    }

    if ($gears) {
        foreach ($gears as $gear) {
            $gearRatios[$gear] ??= [];
            $gearRatios[$gear][] = (int) $num;
        }
    }
}

var_dump(array_sum($partNumbers));

$part2 = 0;

foreach ($gearRatios as $gearRatio) {
    if (count($gearRatio) !== 2) {
        continue;
    }

    $part2 += array_product($gearRatio);
}

var_dump($part2);
