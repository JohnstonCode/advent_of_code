<?php

$inputText = trim(file_get_contents(__DIR__ . '/input.txt'));

$part1 = 0;

foreach (explode("\n", $inputText) as $line) {
    $lineNums = preg_replace('/[^0-9]/', '', $line);
    $nums = str_split($lineNums);

    $part1 += (int)"$nums[0]{$nums[count($nums)-1]}";
}

var_dump($part1);

$numMap = [
    'one' => 1,
    'two' => 2,
    'three' => 3,
    'four' => 4,
    'five' => 5,
    'six' => 6,
    'seven' => 7,
    'eight' => 8,
    'nine' => 9,
];

$part2 = 0;

foreach (explode("\n", $inputText) as $line) {
    $parts = str_split($line);

    $digits = [];

    for ($i = 0; $i < strlen($line); $i++) {
        foreach ($numMap as $spell => $num) {
            if ($line[$i] == $num) {
                $digits[] = $num;

                break;
            }

            $sub = substr($line, $i);
            $pos = strpos($sub, $spell);
            if ($pos === 0) {
                $digits[] = $num;

                break;
            }
        }
    }

    $part2 += (int)"$digits[0]{$digits[count($digits)-1]}";
}

var_dump($part2);

