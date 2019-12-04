<?php

$time_start = microtime(true);

$polymer = file_get_contents(__DIR__ . '/input.txt');
$letters = range('a', 'z');
$shortestPolymer = strlen($polymer);

foreach ($letters as $letterToRemove) {
	$remainingPolymer = str_ireplace($letterToRemove, '', $polymer);

	$previousLength = 0;

	while (strlen($remainingPolymer) !== $previousLength) {
		$previousLength = strlen($remainingPolymer);

		foreach ($letters as $letter) {
			$remainingPolymer = str_replace([
				$letter . strtoupper($letter),
				strtoupper($letter) . $letter,
			], '', $remainingPolymer);
		}
	}

	if (strlen($remainingPolymer) < $shortestPolymer) {
		$shortestPolymer = strlen($remainingPolymer);
	}
}

echo $shortestPolymer . "\n";

echo 'Total execution time in seconds: ' . (microtime(true) - $time_start) . "\n";
