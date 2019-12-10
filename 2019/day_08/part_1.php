<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$imageData = str_split($input, 25 * 6);
$layers = [];

foreach ($imageData as $layer) {
    $layers[] = [
        'zero' => strlen(str_replace(['1', '2'], '', $layer)),
        'one' => strlen(str_replace(['0', '2'], '', $layer)),
        'two' => strlen(str_replace(['0', '1'], '', $layer)),
    ];
}

$result = ['zero' => PHP_INT_MAX, 'result' => 0];
foreach ($layers as $layer) {
    if ($layer['zero'] < $result['zero']) {
        $result['zero'] = $layer['zero'];
        $result['result'] = $layer['one'] * $layer['two'];
    }
}

echo $result['result'] . PHP_EOL;