<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$map = [];
$currentPos = [];
$dirs = [
    '^' => [0, -1],
    '>' => [1, 0],
    'v' => [0, 1],
    '<' => [-1, 0],
];
$currentDir = '^';

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
            $currentPos = [$x, $y];
        }
    }
}

$positions = [];

while (true) {
    $positions["$currentPos[0],$currentPos[1]"] = true;

//    var_dump("$currentPos[0],$currentPos[1]");

    $x = $currentPos[0] + $dirs[$currentDir][0];
    $y = $currentPos[1] + $dirs[$currentDir][1];

    $next = $map[$y][$x] ?? null;
    if (!$next) {
        break;
    }

    if ($next === '#') {
        $currentDir = getNextDir($currentDir);

        continue;
    }

    $currentPos = [$x, $y];
}

var_dump(count($positions));

