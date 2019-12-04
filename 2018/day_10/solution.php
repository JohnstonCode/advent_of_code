<?php

$input = file(__DIR__ . '/input.txt');
$coords = [];

foreach ($input as $line) {
    preg_match_all('/-?\d+/', $line, $matches);
    $coords[] = array_map('intval', [$matches[0][0], $matches[0][1], $matches[0][2], $matches[0][3]]);
}

for ($i = 0; $i < 15000; $i++) {
    $minX = min(array_column($coords, 0));
    $maxX = max(array_column($coords, 0));
    $minY = min(array_column($coords, 1));
    $maxY = max(array_column($coords, 1));
    $box = 65;

    if (($minX + $box) >= $maxX && ($minY + $box) >= $maxY) {
        echo "Time: $i\n";
        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                $match = false;

                foreach ($coords as $coord) {
                    if ($coord[0] === $x && $coord[1] === $y) {
                        $match = true;
                        break;
                    }
                }

                if ($match) {
                    echo '#';
                } else {
                    echo '.';
                }
            }
            echo "\n";
        }
    }

    foreach ($coords as $key => $coord) {
        $coords[$key][0] += $coord[2];
        $coords[$key][1] += $coord[3];
    }
}
