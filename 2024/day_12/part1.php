<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$map = [];

foreach (explode("\n", $rawInput) as $y => $line) {
    foreach (str_split($line) as $x => $char) {
        $map["$x,$y"] = $char;
    }
}

function findPlot(int $x, int $y, string $char, array $map, array &$plot = []): array
{
    $dirs = [[0,-1], [1,0], [0,1], [-1,0]];

    $plot["$x,$y"] = 0;

    foreach ($dirs as $dir) {
        [$cx,$cy] = $dir;
        $nx = $x + $cx;
        $ny = $y + $cy;

        if (!array_key_exists("$nx,$ny", $map)) {
            $plot["$x,$y"]++;

            continue;
        }

        if ($map["$nx,$ny"] !== $char) {
            $plot["$x,$y"]++;
            continue;
        }

        if (array_key_exists("$nx,$ny", $plot)) {
            continue;
        }

        findPlot($nx, $ny, $map["$nx,$ny"], $map, $plot);
    }

    return $plot;
}

$plots = [];

foreach ($map as $xy => $char) {
    [$x, $y] = explode(',', $xy);
    $x = (int) $x;
    $y = (int) $y;

    foreach ($plots as $plot) {
        if (array_key_exists("$x,$y", $plot)) {
            continue 2;
        }
    }

    $plots = [...$plots, findPlot($x, $y, $char, $map)];
}

$total = 0;

foreach ($plots as $plot) {
    $total += count($plot) * array_sum($plot);
}

echo $total . PHP_EOL;
