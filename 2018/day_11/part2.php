<?php

$gridSerialNumber = 9005;

function fuelCellPowerLevel(int $serial, int $x, int $y) {
	$rackId = $x + 10;
	$power = (($rackId * $y) + $serial) * $rackId;
	$hundreds = floor(($power % 1000) / 100);

	return $hundreds - 5;
};

$powerGrid = [];

for ($y = 1; $y <= 300; $y++) {
	for ($x = 1; $x <= 300; $x++) {
		$powerGrid[$x][$y] = fuelCellPowerLevel($gridSerialNumber, $x, $y);
	}
}

$largestTotalPower = 0;
$largestTotalPowerCell = '';


for ($y = 1; $y <= 300; $y++) {
	for ($x = 1; $x <= 300; $x++) {
		echo "$x,$y\n";
		$maxSize = min([301 - $x, 301 - $y]);
		$totalPower = 0;

		for ($size = 1; $size < $maxSize; $size++) {
			for ($xX = 0; $xX < $size; $xX++) {
				$totalPower += $powerGrid[$x + $xX][$y + $size];
			}

			for ($yY = 0; $yY < $size; $yY++) {
				$totalPower += $powerGrid[$x + $size][$y + $yY];
			}

			$totalPower += $powerGrid[$x + $size][$y + $size];

			if ($totalPower > $largestTotalPower) {
				$largestTotalPower = $totalPower;
				$largestTotalPowerCell = "$x,$y,$size";
			}
		}
	}
}

echo $largestTotalPowerCell . "\n";
