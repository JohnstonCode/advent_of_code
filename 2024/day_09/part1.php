<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$diskMap = [];

$fileIndex = 0;

for ($i = 0; $i < strlen($rawInput); $i++) {
    if ($i & 1) {
        $diskMap = [...$diskMap, ...array_fill(0, $rawInput[$i], '.')];
    } else {
        $diskMap = [...$diskMap, ...array_fill(0, $rawInput[$i], $fileIndex)];
        $fileIndex++;
    }
}

//var_dump($diskMap);
//die();

foreach ($diskMap as $key => $block) {
    if (is_integer($block)) {
        continue;
    }

    $endBlock = array_pop($diskMap);

    while(!is_integer($endBlock)) {
        $endBlock = array_pop($diskMap);
    }

    $diskMap[$key] = $endBlock;
}

$checksum = 0;
$diskMap = array_values($diskMap);

for ($i = 0; $i < count($diskMap); $i++) {
    $checksum += $i * $diskMap[$i];
}

echo $checksum . PHP_EOL;
