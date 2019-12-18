<?php

ini_set('memory_limit','20G');

$handle = fopen(__DIR__ . '/input.txt', 'r');
$grid = [];
$y = 0;
$allKeys = [];
$queue = new \DS\Deque();

while (($line = fgets($handle)) !== false) {
    foreach (str_split($line) as $index => $value) {
        $grid[$y][$index] = $value;

        if ($value === '@') {
            $queue->push([$index, $y, 0, []]);
        }

        if (preg_match('/^[a-z]$/', $value)) {
            $allKeys[] = $value;
        }
    }

    $y++;
}

fclose($handle);

$moves = [[0,1], [0,-1], [-1, 0], [1, 0]];
$visited = new \Ds\Set();

while ($queue->count()) {
    if ($visited->count() % 10000000 === 0) {
        $visited = $visited->slice(-1, 1000000);
    }
    [$x, $y, $steps, $keys] = $queue->shift();
    sort($keys);
    $key = "$x,$y,$steps," . implode(',', $keys);
    if ($visited->contains($key)) {
        continue;
    }
    $visited->add($key);

    if (!isset($grid[$y][$x]) || $grid[$y][$x] === '#') {
        continue;
    }

    if (preg_match('/^[A-Z]$/', $grid[$y][$x]) && !in_array(strtolower($grid[$y][$x]), $keys)) {
        continue;
    }

    if (preg_match('/^[a-z]$/', $grid[$y][$x])) {
        $keys = array_unique([...$keys, $grid[$y][$x]]);
        if (count($keys) === count($allKeys)) {
            echo $steps . PHP_EOL;
            break;
        }
    }

    for ($i = 0; $i < 4; $i++) {
        [$XX, $YY] = $moves[$i];
        $X = $x + $XX;
        $Y = $y + $YY;

        $queue->push([$X, $Y, $steps + 1, $keys]);
    }

//    var_dump($keys);
}

//echo $steps . PHP_EOL;

//var_dump($grid);
