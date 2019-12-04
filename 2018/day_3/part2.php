<?php

ini_set('memory_limit', '1024M');

$input = file_get_contents(__DIR__ . '/input.txt');

$fabricGrid = [];
$claims = [];

foreach (explode("\n", $input) as $line) {
	preg_match("/^#(?<id>\d*)\s\@\s(?<left>\d*),(?<top>\d*):\s(?<width>\d*)x(?<height>\d*)$/m", $line, $claim);

	$claims[$claim['id']] = $claim['id'];

	for ($width = 1; $width <= $claim['width']; ++$width) {
		for ($height = 1; $height <= $claim['height']; ++$height) {
			$x = $claim['left'] + $width;
			$y = $claim['top'] + $height;

			$fabricGrid[$x][$y][] = $claim['id'];
		}
	}
}

foreach ($fabricGrid as $row) {
	foreach ($row as $items) {
		if (count($items) > 1) {
			foreach ($items as $claimId) {
				unset($claims[$claimId]);
			}
		}
	}
}

echo end($claims) . "\n";
