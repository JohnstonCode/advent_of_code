<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

$map = [];
$antennas = [];

foreach (explode("\n", $rawInput) as $y => $line) {
    foreach (str_split($line) as $x => $char) {
        $map[$y][$x] = $char;

        if (preg_match('/[a-zA-Z0-9]/', $char)) {
            $antennas[] = [$char, $x, $y];
        }
    }
}

$locations = [];

for ($i = 0; $i < count($antennas); $i++) {
    for ($j = 0; $j < count($antennas); $j++) {
        if ($i == $j) {
            continue;
        }

        $a = $antennas[$i];
        $b = $antennas[$j];

        if ($a[0] !== $b[0]) {
            continue;
        }

        $diff = [$a[1] - $b[1], $a[2] - $b[2]];
        $pos = [$a[1] + $diff[0], $a[2] + $diff[1]];
        $neg = [$b[1] - $diff[0], $b[2] - $diff[1]];

        if ($map[$pos[1]][$pos[0]] ?? false) {
            $locations[] = sprintf('%s,%s', $pos[0], $pos[1]);
        }

        if ($map[$neg[1]][$neg[0]] ?? false) {
            $locations[] = sprintf('%s,%s', $neg[0], $neg[1]);
        }
    }
}

var_dump(count(array_unique($locations)));
