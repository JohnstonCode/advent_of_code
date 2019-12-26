<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$grid = [];

foreach (explode("\n", $input) as $y => $line) {
    foreach (str_split($line) as $x => $char) {
        $grid[$y][$x] = $char;
    }
}

$previous = [];

while (true) {
    $newGrid = [];

    for ($y = 0; $y < 5; $y++) {
        for ($x = 0; $x < 5; $x++) {
            $top = ($grid[$y+1][$x] ?? '');
            $right = ($grid[$y][$x+1] ?? '');
            $bottom = ($grid[$y-1][$x] ?? '');
            $left = ($grid[$y][$x-1] ?? '');
            $values = array_count_values([$top, $right, $bottom, $left]);

            if ($grid[$y][$x] === '.') {
                if (!isset($values['#'])) {
                    $newGrid[$y][$x] = '.';
                    continue;
                }

                if ($values['#'] === 1 || $values['#'] === 2) {
                    $newGrid[$y][$x] = '#';
                    continue;
                }

                $newGrid[$y][$x] = '.';
                continue;
            }

            if ($grid[$y][$x] === '#') {
                if (isset($values['#']) && $values['#'] === 1) {
                    $newGrid[$y][$x] = '#';
                } else {
                    $newGrid[$y][$x] = '.';
                }
            }
        }
    }

    $pattern = '';

    // echo PHP_EOL;
    for ($y = 0; $y < 5; $y++) {
        for ($x = 0; $x < 5; $x++) {
            $pattern .= $newGrid[$y][$x];
            // echo $newGrid[$y][$x];
        }
        // echo PHP_EOL;
    }
    // echo PHP_EOL;

    $grid = $newGrid;

    if (in_array($pattern, $previous)) {
        echo $pattern . PHP_EOL;
        break;
    }

    $previous[] = $pattern;
}

$result = 0;
$pow2 = 1;

for ($y = 0; $y < 5; $y++) {
    for ($x = 0; $x < 5; $x++) {
        if ($grid[$y][$x] === '#') {
            $result += $pow2;
        }

        $pow2 *= 2;
    }
}

echo $result . PHP_EOL;
