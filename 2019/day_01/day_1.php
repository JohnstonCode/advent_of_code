<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$inputs = explode("\n", $input);
$sum = 0;
$totalFuel = 0;

function calculateFuelMass(int $mass): int {
    $fuelMass = floor($mass / 3) - 2;

    if ($fuelMass <= 0) {
        return 0;
    }

    return $fuelMass += calculateFuelMass($fuelMass);
};

foreach ($inputs as $mass) {
    $sum += floor($mass / 3) - 2;
    $totalFuel += calculateFuelMass($mass);
}

echo $sum . PHP_EOL;

echo $totalFuel . PHP_EOL;
