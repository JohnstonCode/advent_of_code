<?php

$time_start = microtime(true);

$input = file(__DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$formattedArray = [];
$guardData = [];
$sleepStart = 0;
$currentGuard = 0;

foreach ($input as $line) {
	preg_match('/^\[(?<date>\d+\-\d+-\d+\s\d+:\d+)]\s(?<status>.*)$/', $line, $matches);

	$formattedArray[$matches['date']] = $matches['status'];
}

ksort($formattedArray);

foreach ($formattedArray as $key => $value) {
	if (0 === strpos($value, 'Guard')) {
		$currentGuard = preg_replace('/[^0-9]/', '', $value);
	} elseif ('wakes up' === $value) {
		$minute = (int) substr($key, -2);
		$guardData[$currentGuard]['totalSleepTime'] = $guardData[$currentGuard]['totalSleepTime'] + ($minute - $sleepStart);

		for ($i = $sleepStart; $i < $minute; ++$i) {
			++$guardData[$currentGuard]['sleepMinute'][$i];
		}
	} elseif ('falls asleep' === $value) {
		$sleepStart = (int) substr($key, -2);
		$guardData[$currentGuard]['id'] = $currentGuard;
	}
}

$max = array_reduce($guardData, function ($carry, $item) {
	$carryMaxSleptMinute = max($carry['sleepMinute']);
	$itemMaxSleptMinute = max($item['sleepMinute']);

	if ($carryMaxSleptMinute > $itemMaxSleptMinute) {
		return $carry;
	}

	return $item;
});

$maxMostSleptMinute = max($max['sleepMinute']);
$mostSleptMinute = array_search($maxMostSleptMinute, $max['sleepMinute']);

echo $max['id'] * $mostSleptMinute . "\n";
echo 'Total execution time in seconds: ' . (microtime(true) - $time_start) . "\n";
