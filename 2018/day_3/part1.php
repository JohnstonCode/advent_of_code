<?php

ini_set('memory_limit', '1024M');

$input = file_get_contents(__DIR__ . '/input.txt');

$fabricGrid = [];
$overlaps = 0;

foreach (explode("\n", $input) as $line) {
	preg_match("/^#(?<id>\d*)\s\@\s(?<left>\d*),(?<top>\d*):\s(?<width>\d*)x(?<height>\d*)$/", $line, $claim);

	for ($width = 1; $width <= $claim['width']; ++$width) {
		for ($height = 1; $height <= $claim['height']; ++$height) {
			$x = $claim['left'] + $width;
			$y = $claim['top'] + $height;

			if (!isset($fabricGrid[$x][$y])) {
				$fabricGrid[$x][$y][] = $claim['id'];
				continue;
			}

			if (1 === count($fabricGrid[$x][$y])) {
				++$overlaps;
			}

			$fabricGrid[$x][$y][] = $claim['id'];
		}
	}
}

echo $overlaps . "\n";
