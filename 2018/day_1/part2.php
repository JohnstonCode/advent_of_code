<?php

$input = file_get_contents(__DIR__ . '/input.txt');

$frequency = 0;
$frequencies = [0 => 1];
$changes = array_filter(explode("\n", $input));

while (!in_array(2, $frequencies)) {
	foreach ($changes as $change) {
		$frequency += (int) $change;

		@++$frequencies[$frequency];
	}
}

echo array_search(2, $frequencies) . "\n";
