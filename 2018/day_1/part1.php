<?php

$input = file_get_contents(__DIR__ . '/input.txt');

$frequency = 0;
$changes = array_filter(explode("\n", $input));

foreach ($changes as $change) {
	$frequency += (int) $change;
}

echo $frequency . "\n";
