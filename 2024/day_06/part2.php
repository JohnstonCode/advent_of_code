<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$map = [];
$startPos = [];
$dirs = [
    '^' => [0, -1],
    '>' => [1, 0],
    'v' => [0, 1],
    '<' => [-1, 0],
];
$startDir = '^';

function getNextDir(string $dir): string {
    return match($dir) {
        '^' => '>',
        '>' => 'v',
        'v' => '<',
        '<' => '^',
    };
}

foreach (explode("\n", $rawInput) as $y => $line) {
    foreach (str_split($line) as $x => $char) {
        $map[$y][$x] = $char;

        if ($char === '^') {
            $startPos = [$x, $y];
        }
    }
}

$loopCount = 0;

for ($y = 0; $y < count($map); $y++) {
    for ($x = 0; $x < count($map[0]); $x++) {
        if ($map[$y][$x] !== '.') {
            continue;
        }

        $mapCopy = $map;
        $mapCopy[$y][$x] = '#';
        $positions = [];
        $currentPos = $startPos;
        $currentDir = $startDir;

        while (true) {
            if ($positions["$currentPos[0],$currentPos[1],$currentDir"] ?? false) {
                $loopCount++;

                break;
            }

            $positions["$currentPos[0],$currentPos[1],$currentDir"] = true;

            $xx = $currentPos[0] + $dirs[$currentDir][0];
            $yy = $currentPos[1] + $dirs[$currentDir][1];

            $next = $mapCopy[$yy][$xx] ?? null;
            if (!$next) {
                break;
            }

            if ($next === '#') {
                $currentDir = getNextDir($currentDir);

                continue;
            }

            $currentPos = [$xx, $yy];
        }
    }
}

var_dump($loopCount);

