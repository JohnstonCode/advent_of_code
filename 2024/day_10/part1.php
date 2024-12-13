<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$map = [];

foreach (explode("\n", $rawInput) as $y => $line) {
    foreach (str_split($line) as $x => $char) {
        $map[$y][$x] = (int) $char;
    }
}

function dfs($x, $y, array &$seen = []): int {
    global $map;

    if (isset($seen["$x,$y"])) {
        return 0;
    }

    $seen["$x,$y"] = $map[$y][$x];

    if ($map[$y][$x] === 9) {
        return 1;
    }

    $result = 0;

    $dirs = [
        [0, -1],
        [1, 0],
        [0, 1],
        [-1, 0]
    ];

    foreach ($dirs as $dir) {
        [$cx, $cy] = $dir;

        $nx = $x + $cx;
        $ny = $y + $cy;

        if (!isset($map[$ny][$nx])) {
            continue;
        }

        if ($map[$ny][$nx] === (1 + $map[$y][$x])) {
            $result += dfs($nx, $ny, $seen);
        }
    }

    return $result;
}

$sum = 0;

foreach ($map as $y => $line) {
    foreach ($line as $x => $height) {
        if ($height === 0) {
            $sum += dfs($x, $y);
        }
    }
}

echo $sum . PHP_EOL;
