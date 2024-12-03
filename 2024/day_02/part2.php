<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$reports = [];

foreach (explode("\n", $rawInput) as $line) {
    $levels = [];

    foreach (explode(" ", $line) as $num) {
        $levels[] = (int) $num;
    }

    $reports[] = $levels;
}

function isSafe(array $levels): bool {
    $i = 0;

    while ($i < count($levels) - 1) {
        if ($levels[$i] === $levels[$i+1]) {
            return false;
        }

        $diff = abs($levels[$i] - $levels[$i+1]);

        if ($diff < 1 || $diff > 3) {
            return false;
        }

        $i++;
    }

    $inc = $levels;
    $dec = $levels;
    sort($inc);
    rsort($dec);

    if ($inc !== $levels && $dec !== $levels) {
        return false;
    }

    return true;
}

$safe = 0;

foreach ($reports as $report) {
    if (isSafe($report)) {
        $safe++;

        continue;
    }

    for ($i = 0; $i < count($report); $i++) {
        $rep = $report;
        unset($rep[$i]);

        if (isSafe(array_values($rep))) {
            $safe++;

            continue 2;
        }
    }
}

var_dump($safe);

