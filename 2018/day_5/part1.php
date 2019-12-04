<?php

$time_start = microtime(true);

$polymer = file_get_contents(__DIR__ . '/input.txt');
$letters = range('a', 'z');
$previousLength = 0;

while (strlen($polymer) !== $previousLength) {
	$previousLength = strlen($polymer);

	foreach ($letters as $letter) {
		$polymer = str_replace([
			$letter . strtoupper($letter),
			strtoupper($letter) . $letter,
		], '', $polymer);
	}
}

echo strlen($polymer) . "\n";

echo 'Total execution time in seconds: ' . (microtime(true) - $time_start) . "\n";
