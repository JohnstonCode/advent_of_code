<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$stones = [];

foreach (explode(' ', $rawInput) as $num) {
    $stones[] = (int) $num;
}

$cache = [];

function solve(int $stone, int $count): int {
    global $cache;

    if ($count === 0) {
        return 1;
    }

    if (isset($cache["$stone,$count"])) {
        return $cache["$stone,$count"];
    }

    if ($stone === 0) {
        $result = solve(1, $count - 1);
    } else if (strlen($stone) % 2 === 0) {
        [$a, $b] = str_split((string) $stone, strlen($stone) / 2);
        $b = $b ?: 0;

        $result = 0;
        $result += solve((int) $a, $count - 1);
        $result += solve((int) $b, $count - 1);
    } else {
        $result = solve($stone * 2024, $count - 1);
    }

    $cache["$stone,$count"] = $result;

    return $result;
}

$total = 0;

foreach ($stones as $stone) {
    $total += solve($stone, 75);
}

echo $total . PHP_EOL;
