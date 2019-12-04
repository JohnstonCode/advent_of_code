<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$boxIds = explode("\n", $input);
$twoLetterCount = 0;
$threeLetterCount = 0;

foreach ($boxIds as $boxId) {
	$letters = str_split($boxId);
	$letterCounts = array_count_values($letters);

	if (in_array(2, array_values($letterCounts))) {
		++$twoLetterCount;
	}

	if (in_array(3, array_values($letterCounts))) {
		++$threeLetterCount;
	}
}

echo $twoLetterCount * $threeLetterCount;
echo "\n";
