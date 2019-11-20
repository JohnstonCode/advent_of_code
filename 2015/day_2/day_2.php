<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$dimentions = explode("\n", $input);

$totalPaperSize = 0;

foreach ($dimentions as $dimention) {
    [$length, $width, $height] = explode("x", $dimention);

    $lw = (int) $length * (int) $width;
    $wh = (int) $width * (int) $height;
    $hl = (int) $height * (int) $length;

    $lengthSurface = 2 * $lw;
    $widthSurface = 2 * $wh;
    $heightSurface = 2 * $hl;
    
    $total = $lengthSurface + $widthSurface + $heightSurface + min($lw, $wh, $hl);

    $totalPaperSize += $total;
}

echo $totalPaperSize . PHP_EOL;

$ribbonLength = 0;

foreach ($dimentions as $dimention) {
    $arr = explode("x", $dimention);
    sort($arr, SORT_NUMERIC);
    $poped = array_pop($arr);

    $wrap = $arr[0] + $arr[0] + $arr[1] + $arr[1];
    $bow = $arr[0] * $arr[1] * $poped;

    $ribbonLength += $wrap + $bow;
}

echo $ribbonLength . PHP_EOL;
