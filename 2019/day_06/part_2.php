<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$orbitMap = explode("\n", $input);
$orbits = [];

foreach ($orbitMap as $orbit) {
    [$orbited, $orbiting] = explode(')', $orbit);

    $orbits[$orbiting] = $orbited;
}

$distances = [];
$objectsOrbitingAnother = array_keys($orbits);

for ($i = 0; $i < count($objectsOrbitingAnother); $i++) {
    $currentObject = $objectsOrbitingAnother[$i];
    $nextObject = $orbits[$currentObject];
    $visited = [];
    $distanceToObject = 0;

    while ($nextObject) {
        $nextObject = @$orbits[$nextObject];
        $distanceToObject += 1;

        $visited[] = [$nextObject, $distanceToObject];
    }

    $distances[$currentObject] = $visited;
}

for ($i = 0; $i < count($distances['YOU']); $i++) {
    [$youObj, $youDist] = $distances['YOU'][$i];

    for ($j = 0; $j < count($distances['SAN']); $j++) {
        [$sanObj, $sanDist] = $distances['SAN'][$j];

        if ($youObj === $sanObj) {
            echo $youDist + $sanDist . PHP_EOL;
            break 2;
        }
    }
}
