<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$map = explode("\n", $input);
$height = count($map);
$width = strlen($map[0]);

$x = 26;
$y = 28;
$seen = [];

for ($yy = 0; $yy < $height; $yy++) {
    for ($xx = 0; $xx < $width; $xx++) {
        if ($map[$yy][$xx] === '#' && ($yy !== $y || $xx !== $x)) {
            $angle = rad2deg(atan2($yy - $y, $xx - $x));

            $angle += 90;

            if ($angle < 0) {
                $angle += 360;
            }

            $seen["$angle"][] = "$xx,$yy";
        }
    }
}

uksort($seen, function ($a, $b) {
    return (float) $a <=> (float) $b;
});

foreach ($seen as &$array) {
    usort($array, function ($a, $b) use ($x, $y) {
       [$aX, $aY] = explode(',', $a);
       [$bX, $bY] = explode(',', $b);

       return (abs($aY - $y) + abs($aX - $x)) <=> (abs($bY - $y) + abs($bX - $x));
    });
}

$i = 1;

while (true) {
    foreach ($seen as $array) {
        $current = array_shift($array);

        if ($i === 200) {
            [$x, $y] = explode(',', $current);
            echo ((int) $x * 100) + (int) $y . PHP_EOL;
            break 2;
        }

        $i++;
    }
}
