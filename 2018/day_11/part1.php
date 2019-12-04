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
		$totalPower = 0;

		for ($yY = 0; $yY < 3; $yY++) {
			for ($xX = 0; $xX < 3; $xX++) {
				$totalPower += $powerGrid[$x + $xX][$y + $yY];
			}
		}

		if ($totalPower > $largestTotalPower) {
			$largestTotalPower = $totalPower;
			$largestTotalPowerCell = "$x,$y";
		}
	}
}

echo $largestTotalPowerCell . "\n";
