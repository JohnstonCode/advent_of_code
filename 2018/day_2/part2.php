<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$boxIds = explode("\n", $input);
$possibleMatches = [];

foreach ($boxIds as $boxId1) {
	foreach ($boxIds as $boxId2) {
		if (in_array($boxId2, $possibleMatches)) {
			continue;
		}

		if (1 === levenshtein($boxId1, $boxId2)) {
			$possibleMatches[] = $boxId2;
		}
	}
}

if (2 === count($possibleMatches)) {
	$position = strspn($possibleMatches[0] ^ $possibleMatches[1], "\0");
	echo substr_replace($possibleMatches[0], '', $position, 1) . "\n";
} else {
	echo "No 1 diff match found!\n";
}
