<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$wordSearch = [];

foreach (explode("\n", $rawInput) as $line) {
    $wordSearch[] = str_split($line);
}

$xmas = 0;

foreach ($wordSearch as $y => $line) {
    foreach ($line as $x => $char) {
        if ($char !== 'A') {
            continue;
        }

        $topLeft = $wordSearch[$y-1][$x-1] ?? '';
        $bottomRight = $wordSearch[$y+1][$x+1] ?? '';
        $topRight = $wordSearch[$y-1][$x+1] ?? '';
        $bottomLeft = $wordSearch[$y+1][$x-1] ?? '';

        if (
            (($topLeft === 'M' && $bottomRight === 'S') || ($topLeft === 'S' && $bottomRight === 'M')) &&
            (($topRight === 'M' && $bottomLeft === 'S') || ($topRight === 'S' && $bottomLeft === 'M'))
        ) {
            $xmas++;
        }
    }
}

var_dump($xmas);
