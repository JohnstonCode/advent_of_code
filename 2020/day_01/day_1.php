<?php

$inputs = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

for ($x = 0; $x < count($inputs) - 1; ++$x) {
    for ($y = $x+1; $y < count($inputs); ++$y) {
        if (($inputs[$x] + $inputs[$y]) === 2020) {
            echo ($inputs[$x] * $inputs[$y]) . PHP_EOL;

            break 2;
        }
    }
}

for ($x = 0; $x < count($inputs) - 2; ++$x) {
    for ($y = $x + 1; $y < count($inputs) - 1; ++$y) {
        for ($z = $y + 1; $z < count($inputs); ++$z) {
            if (($inputs[$x] + $inputs[$y] + $inputs[$z]) === 2020) {
                echo ($inputs[$x] * $inputs[$y] * $inputs[$z]) . PHP_EOL;

                break 3;
            }
        }
    }
}
