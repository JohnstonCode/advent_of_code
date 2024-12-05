<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$wordSearch = [];

foreach (explode("\n", $rawInput) as $line) {
    $wordSearch[] = str_split($line);
}

$xmas = 0;
$positions = [
    [[0,-1], [0,-2], [0,-3]],
    [[1,-1], [2,-2], [3,-3]],
    [[1,0], [2,0], [3,0]],
    [[1,1], [2,2], [3,3]],
    [[0,1], [0,2], [0,3]],
    [[-1,1], [-2,2], [-3,3]],
    [[-1,0], [-2,0], [-3,0]],
    [[-1,-1], [-2,-2], [-3,-3]],
];

$xmasCheck1 = ['X', 'M', 'A', 'S'];
$xmasCheck2 = ['S', 'A', 'M', 'X'];

foreach ($wordSearch as $y => $line) {
    foreach ($line as $x => $char) {
        if ($char !== 'X') {
            continue;
        }

        $words = [];

        foreach ($positions as $position) {
            $letters = ['X'];

            foreach ($position as $cord) {
                $letters[] = $wordSearch[$y+$cord[1]][$x+$cord[0]] ?? '';
            }

            $words[] = $letters;
        }

        foreach ($words as $word) {
            if ($word === $xmasCheck1 || $word === $xmasCheck2) {
                $xmas++;
            }
        }
    }
}

var_dump($xmas);
