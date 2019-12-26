<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$initialGrid = [];

foreach (explode("\n", $input) as $y => $line) {
    foreach (str_split($line) as $x => $char) {
        $initialGrid[$y][$x] = $char;
    }
}

$grid = [];
$directions = [[1,0], [0,1], [-1,0], [0,-1]];
$adjacent = [];

for ($level = -200; $level < 201; $level++) {
    for ($y = 0; $y < 5; $y++) {
        for ($x = 0; $x < 5; $x++) {
            if ($y === 2 && $x === 2) {
                continue;
            }

            $key = "$y,$x,$level";
            $grid[$key] = '.';
            
            if ($level === 0) {
                $grid[$key] = $initialGrid[$y][$x];
            }

            $adjacent[$key] = $adjacent[$key] ?? [];

            for ($i = 0; $i < 4; $i++) {
                [$X, $Y] = $directions[$i];
                $xx = $x + $X;
                $yy = $y + $Y;

                if ($y === 1 && $x === 2) {
                    $adjacent[$key] = [
                        "0,2,$level",
                        "1,1,$level",
                        "1,3,$level",
                        "0,0," . ($level + 1),
                        "0,1," . ($level + 1),
                        "0,2," . ($level + 1),
                        "0,3," . ($level + 1),
                        "0,4," . ($level + 1),
                    ];
                    continue;
                }

                if ($y === 2 && $x === 1) {
                    $adjacent[$key] = [
                        "2,0,$level",
                        "1,1,$level",
                        "3,1,$level",
                        "0,0," . ($level + 1),
                        "1,0," . ($level + 1),
                        "2,0," . ($level + 1),
                        "3,0," . ($level + 1),
                        "4,0," . ($level + 1),
                    ];
                    continue;
                }

                if ($y === 2 && $x === 3) {
                    $adjacent[$key] = [
                        "1,3,$level",
                        "2,4,$level",
                        "3,3,$level",
                        "0,4," . ($level + 1),
                        "1,4," . ($level + 1),
                        "2,4," . ($level + 1),
                        "3,4," . ($level + 1),
                        "4,4," . ($level + 1),
                    ];
                    continue;
                }

                if ($y === 3 && $x === 2) {
                    $adjacent[$key] = [
                        "3,1,$level",
                        "4,2,$level",
                        "3,3,$level",
                        "4,0," . ($level + 1),
                        "4,1," . ($level + 1),
                        "4,2," . ($level + 1),
                        "4,3," . ($level + 1),
                        "4,4," . ($level + 1),
                    ];
                    continue;
                }

                if ($yy >= 0 && $yy < 5 && $xx >= 0 && $xx < 5) {
                    $adjacent[$key][] = "$yy,$xx,$level";
                }
                
                if ($yy < 0 && $level > -100) {
                    $adjacent[$key][] = "1,2," . ($level - 1);
                }

                if ($yy > 4 && $level < 100) {
                    $adjacent[$key][] = "3,2," . ($level - 1);
                }

                if ($xx < 0 && $level > -100) {
                    $adjacent[$key][] = "2,1," . ($level - 1);
                }

                if ($xx > 4 && $level < 100) {
                    $adjacent[$key][] = "2,3," . ($level - 1);
                }
            }
        }
    }
}

for ($i = 0; $i < 200; $i++) {
    $newGrid = [];

    foreach ($grid as $key => $value) {
        $values = array_count_values(array_map(function ($item) use ($grid){
            return $grid[$item] ?? '';
        }, $adjacent[$key]));

        if ($value === '.') {
            if (!isset($values['#'])) {
                $newGrid[$key] = '.';
                continue;
            }

            if ($values['#'] === 1 || $values['#'] === 2) {
                $newGrid[$key] = '#';
                continue;
            }

            $newGrid[$key] = '.';
            continue;
        }

        if ($value === '#') {
            if (isset($values['#']) && $values['#'] === 1) {
                $newGrid[$key] = '#';
            } else {
                $newGrid[$key] = '.';
            }
        }
    }

    $grid = $newGrid;
}

echo array_count_values($grid)['#'] . PHP_EOL;
