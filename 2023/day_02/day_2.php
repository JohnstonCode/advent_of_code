<?php

$inputText = trim(file_get_contents(__DIR__ . '/input.txt'));

$bag = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
];

$part1 = 0;

foreach (explode("\n", $inputText) as $id => $line) {
    $game = preg_replace('/^Game \d+\: /', '', $line);

    $subsets = explode('; ', $game);
    foreach ($subsets as $subset) {
        $cubes = explode(', ', $subset);

        foreach ($cubes as $c) {
            [$count, $color] = explode(' ', $c);

            if ($count > $bag[$color]) {
                continue 3;
            }
        }
    }

    $part1 += ($id + 1);
}

var_dump($part1);

$part2 = 0;

foreach (explode("\n", $inputText) as $id => $line) {
    $game = preg_replace('/^Game \d+\: /', '', $line);

    $subsets = explode('; ', $game);

    $bag = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];

    foreach ($subsets as $subset) {
        $cubes = explode(', ', $subset);

        foreach ($cubes as $c) {
            [$count, $color] = explode(' ', $c);

            $bag[$color] = max($bag[$color], $count);
        }
    }

    $part2 += array_product(array_values($bag));
}

var_dump($part2);
