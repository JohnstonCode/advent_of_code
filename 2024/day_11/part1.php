<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$stones = [];

foreach (explode(' ', $rawInput) as $num) {
    $stones[] = (int) $num;
}

for ($i = 0; $i < 25; $i++) {
    $tmpStones = [];

    foreach ($stones as $stone) {
        if ($stone === 0) {
            $tmpStones[] = 1;
            continue;
        }

        if (strlen($stone) % 2 === 0) {
            [$a, $b] = str_split((string) $stone, strlen($stone) / 2);
            $tmpStones[] = (int) $a;

            $b = ltrim($b, '0');
            $tmpStones[] = (int) $b ?: 0;

            continue;
        }

        $tmpStones[] = $stone * 2024;
    }

    $stones = $tmpStones;
}

echo count($stones) . PHP_EOL;
