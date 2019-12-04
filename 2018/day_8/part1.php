<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$numbers = array_map('intval', explode(' ', $input));
$sum = 0;

buildTree();

echo $sum . "\n";

function buildTree() {
    global $sum;
    global $numbers;

    $chlidCount = array_shift($numbers);
    $metaDataCount = array_shift($numbers);

    $childNodes = [];

    for ($i = 0; $i < $chlidCount; $i++) {
        $childNodes[] = buildTree();
    }

    $metadata = [];

    for ($j = 0; $j < $metaDataCount; $j++) {
        $metadataEntry = array_shift($numbers);
        $metadata[] = $metadataEntry;
        $sum += $metadataEntry;
    }

    return ['childNodes' => $childNodes, 'metadata' => $metadata];
}