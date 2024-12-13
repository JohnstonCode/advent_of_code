<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$diskMap = [];

$fileIndex = 0;

for ($i = 0; $i < strlen($rawInput); $i++) {
    if (!$rawInput[$i]) {
        continue;
    }

    if ($i & 1) {
        $diskMap[] = array_fill(0, $rawInput[$i], '.');
    } else {
        $diskMap[] = array_fill(0, $rawInput[$i], $fileIndex);
        $fileIndex++;
    }
}

$revMap = array_reverse(array_filter($diskMap, fn ($block) => is_integer($block[0])));

foreach ($revMap as $file) {
    if (!is_integer($file[0])) {
        continue;
    }

    $fileKey = array_search($file, $diskMap);

    $freeSpace = array_filter($diskMap, fn ($block, $key) => $block[0] === '.' && count($block) >= count($file) && $key <= $fileKey, ARRAY_FILTER_USE_BOTH);

    foreach ($freeSpace as $index => $block) {
        $diskMap[$fileKey] = array_fill(0, count($file), '.');
        $diskMap[$index] = $file;

        if (count($block) !== count($file)) {
            array_splice($diskMap, $index + 1, 0, [array_fill(0, count($block) - count($file), '.')]);
        }

        continue 2;
    }
}

$checksum = 0;
$diskMap = array_merge(...$diskMap);

for ($i = 0; $i < count($diskMap); $i++) {
    if (!is_integer($diskMap[$i])) {
        continue;
    }

    $checksum += $i * $diskMap[$i];
}

echo $checksum . PHP_EOL;
