<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$map = explode("\n", $input);
$height = count($map);
$width = strlen($map[0]);

$result = [];
$coords = [];

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        if ($map[$y][$x] !== '#') {
            continue;
        }

        $seen = [];

        for ($yy = 0; $yy < $height; $yy++) {
            for ($xx = 0; $xx < $width; $xx++) {
                if ($map[$yy][$xx] === '#' && ($yy !== $y || $xx !== $x)) {
                    $angle = rad2deg(atan2($yy - $y, $xx - $x));
                    $seen[] = $angle;
                }
            }
        }

        if (count(array_unique($seen)) > count($result)) {
            $result = array_unique($seen);
            $coords = [$x, $y];
        }
    }
}

echo count($result) . PHP_EOL;
echo implode(',', $coords) . PHP_EOL;
