<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$wires = explode("\n", $input);
$wire1 = explode(',', $wires[0]);
$wire2 = explode(',', $wires[1]);

function wirePositions(array $positions) {
    $return = [];
    $positionY = 0;
    $positionX = 0;

    foreach ($positions as $position) {
        preg_match_all('/([RULD])(\d+)/', $position, $matches);
        $direction = $matches[1][0];
        $count = $matches[2][0];
    
        for ($i = 0; $i < $count; $i++) {
            switch ($direction) {
                case 'U':
                    $positionY += 1;
                    break;
                case 'D':
                    $positionY -= 1;
                    break;
                case 'L':
                    $positionX -= 1;
                    break;
                case 'R':
                    $positionX += 1;
                    break;
            }
    
            $return[] = "$positionY,$positionX";
        }
    }

    return $return;
}

function wirePositionsWithSteps(array $positions) {
    $return = [];
    $positionY = 0;
    $positionX = 0;
    $steps = 0;

    foreach ($positions as $position) {
        preg_match_all('/([RULD])(\d+)/', $position, $matches);
        $direction = $matches[1][0];
        $count = $matches[2][0];
    
        for ($i = 0; $i < $count; $i++) {
            switch ($direction) {
                case 'U':
                    $positionY += 1;
                    break;
                case 'D':
                    $positionY -= 1;
                    break;
                case 'L':
                    $positionX -= 1;
                    break;
                case 'R':
                    $positionX += 1;
                    break;
            }
    
            $steps++;

            if (!isset($return["$positionY,$positionX"])) {
                $return["$positionY,$positionX"] = $steps;
            }
        }
    }

    return $return;
}

$wire1Positions = wirePositions($wire1);
$wire2Positions = wirePositions($wire2);

$wirePositions = array_intersect($wire1Positions, $wire2Positions);

$man = array_map(function ($item) {
    [$y, $x] = explode(',', $item);

    return abs($y) + abs($x);
}, $wirePositions);

sort($man);

echo $man[0] . PHP_EOL;


$wire1PositionsWithSteps = wirePositionsWithSteps($wire1);
$wire2PositionsWithSteps = wirePositionsWithSteps($wire2);

$t = array_map(function ($item) use ($wire1PositionsWithSteps, $wire2PositionsWithSteps) {
    return $wire1PositionsWithSteps[$item] + $wire2PositionsWithSteps[$item];
}, $wirePositions);

sort($t);

echo $t[0] . PHP_EOL;


