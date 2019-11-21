<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$arr = explode("\n", $input);

$grid = [];
$light = [];

$positions = function ($startX, $startY, $endX, $endY) {
    for ($y = $startY; $y <= $endY; $y++) {
        for ($x = $startX; $x <= $endX; $x++) {
            yield $x + $y * 1000;
        }
    }
};

foreach ($arr as $line) {
    preg_match_all('/(.*) (\d+),(\d+) through (\d+),(\d+)/', $line, $matches);
    $action = $matches[1][0];
    $startX = $matches[2][0];
    $startY = $matches[3][0];
    $endX = $matches[4][0];
    $endY = $matches[5][0];
    
    foreach ($positions($startX, $startY, $endX, $endY) as $index) {
        $grid[$index] = $grid[$index] ?? false;
        $light[$index] = $light[$index] ?? 0;

        switch ($action) {
            case 'turn on':
                $grid[$index] = true;
                $light[$index] += 1;
                break;
            case 'turn off':
                $grid[$index] = false;
                $light[$index] = max(0, ($light[$index] - 1));
                break;
            case 'toggle':
                $grid[$index] = !$grid[$index];
                $light[$index] += 2;
                break;
        }
    }
}

$count = count(array_filter($grid, function ($item) { return $item; }));
$light = array_reduce($light, function($carry, $index) { return $carry + $index; }, 0);

echo $count . PHP_EOL;
echo $light . PHP_EOL;
