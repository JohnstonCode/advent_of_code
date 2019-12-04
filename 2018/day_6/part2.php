<?php

/** @var array $input */
$input = file(__DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$maxX = 0;
$maxY = 0;
$minX = 99999;
$minY = 99999;

$points = [];

foreach ($input as $line) {
	$coords = array_map('intval', explode(', ', $line));

	$maxX = $coords[0] > $maxX ? $coords[0] : $maxX;
	$maxY = $coords[1] > $maxY ? $coords[1] : $maxY;
	$minX = $coords[0] < $minX ? $coords[0] : $minX;
	$minY = $coords[1] < $minY ? $coords[1] : $minY;

	$points[] = $coords;
}

$totalDistance = 0;

$calcDist = function($pointA, $pointB) {
	return abs($pointA[0] - $pointB[0]) + abs($pointA[1] - $pointB[1]);
};

for ($y = $minY; $y <= $maxY; $y++) {
	for ($x = $minX; $x <= $maxX; $x++) {
		$distances = [];

		foreach ($points as $point) {
			$distances[] = abs($x - $point[0]) + abs($y - $point[1]);
		}

		if (array_sum($distances) < 10000) {
			$totalDistance++;
		}
	}
}

echo $totalDistance . "\n";
