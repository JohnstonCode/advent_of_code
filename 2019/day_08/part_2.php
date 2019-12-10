<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$layers = str_split($input, 25 * 6);

$image = $layers[0];
unset($layers[0]);

while (false !== $pos = strpos($image, '2')) {
    foreach ($layers as $layer) {
        if ($layer[$pos] !== '2') {
            $image[$pos] = $layer[$pos];

            break;
        }
    }
}

$finalImage = PHP_EOL;

foreach (str_split($image, 25) as $value) {
    $finalImage .= strtr($value, [
        '0' => ' ',
        '1' => '█',
    ]) . PHP_EOL;
}

echo $finalImage . PHP_EOL;