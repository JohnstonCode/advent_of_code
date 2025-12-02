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

function getCornerCount(int $x, int $y, array $positions): int
{
    $dirs = [
        [[0,-1], [-1,-1], [-1,0]],
        [[0,-1], [1,-1], [1,0]],
        [[1,0], [1,1], [0, 1]],
        [[-1, 0], [0, 1], [-1,1]]
    ];
    $corner = 0;

    foreach ($dirs as $dir) {
        foreach ($dir as $d) {
            [$cx, $cy] = $d;
            $nx = $x + $cx;
            $ny = $y + $cy;

            if (in_array("$nx,$ny", $positions)) {
                continue 2;
            }
        }

        $corner++;
    }

    $leftDirs = [[-1, 1], [-1, 1]];
    $rightDirs = [[1,-1], [1,1]];

    $left = sprintf("%s,%s", $x-1, $y);
    $right = sprintf("%s,%s", $x+1, $y);

    if (!in_array($left, $positions)) {
        foreach ($leftDirs as $dir) {
            [$cx, $cy] = $dir;
            $nx = $x + $cx;
            $ny = $y + $cy;

            if (in_array("$nx,$ny", $positions)) {
                $corner++;
            }
        }
    }

    if (!in_array($right, $positions)) {
        foreach ($rightDirs as $dir) {
            [$cx, $cy] = $dir;
            $nx = $x + $cx;
            $ny = $y + $cy;

            if (in_array("$nx,$ny", $positions)) {
                $corner++;
            }
        }
    }

    return $corner;
}

$total = 0;

foreach ($plots as $plot) {
    $positions = array_keys($plot);
    $corners = 0;

    foreach ($positions as $position) {
        [$x, $y] = explode(',', $position);

        $corners += getCornerCount($x, $y, $positions);
    }

    echo $map[array_key_first($plot)] . ' -> ' . $corners . PHP_EOL;

    $total += count($plot) * $corners;
}

echo $total . PHP_EOL;
