<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$orbitMap = explode("\n", $input);
$orbits = [];

foreach ($orbitMap as $orbit) {
    [$orbited, $orbiting] = explode(')', $orbit);

    $orbits[$orbiting] = $orbited;
}

$totalOrbits = 0;
$objectsOrbitingAnother = array_keys($orbits);

for ($i = 0; $i < count($objectsOrbitingAnother); $i++) {
    $currentObject = $objectsOrbitingAnother[$i];
    $nextObject = $orbits[$currentObject];

    while ($nextObject) {
        $nextObject = @$orbits[$nextObject];
        $totalOrbits += 1;
    }
}

echo $totalOrbits . PHP_EOL;
