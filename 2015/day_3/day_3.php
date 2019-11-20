<?php

$text = file_get_contents(__DIR__ . '/input.txt');
$moves = str_split($text);

$positions = [];
$positions[0][0] = 1;

$x = 0;
$y = 0;

foreach ($moves as $move) {
    switch ($move) {
        case '^':
            $y++;
            break;
        case '>':
            $x++;
            break;
        case 'v':
            $y--;
            break;
        case '<':
            $x--;
            break;
    }

    $positions[$x][$y] = $positions[$x][$y] ?? 0;
    $positions[$x][$y]++;
}

$count = 0;

foreach ($positions as $row) {
    $count += count($row);
}

echo $count . PHP_EOL;

$positions = [];
$positions[0][0] = 2;

$sX = 0;
$sY = 0;
$rX = 0;
$rY = 0;

$isRobo = false;

foreach ($moves as $move) {
    $x = 0;
    $y = 0;

    switch ($move) {
        case '^':
            $y++;
            break;
        case '>':
            $x++;
            break;
        case 'v':
            $y--;
            break;
        case '<':
            $x--;
            break;
    }

    if ($isRobo) {
        $rX += $x;
        $rY += $y;

        $x = $rX;
        $y = $rY;
    } else {
        $sX += $x;
        $sY += $y;

        $x = $sX;
        $y = $sY;
    }

    $positions[$x][$y] = $positions[$x][$y] ?? 0;
    $positions[$x][$y]++;

    $isRobo = !$isRobo;
}

$count = 0;

foreach ($positions as $row) {
    $count += count($row);
}

echo $count . PHP_EOL;
