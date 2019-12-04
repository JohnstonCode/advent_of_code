<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$numbers = array_map('intval', explode(' ', $input));
$tree = buildTree();
$val = calcVal($tree);

echo $val . "\n";

function calcVal($tree) {
    $childNodes = $tree['childNodes'];
    $metadata = $tree['metadata'];

    if (count($childNodes) === 0) {
        return array_sum($metadata);
    }

    $sum = 0;

    foreach ($metadata as $index) {
        $index--;

        if (isset($childNodes[$index])) {
            $sum += calcVal($childNodes[$index]);
        }
    }

    return $sum;
}

function buildTree() {
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
    }

    return ['childNodes' => $childNodes, 'metadata' => $metadata];
}
