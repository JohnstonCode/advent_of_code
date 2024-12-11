<?php

$rawInput = file_get_contents(__DIR__ . '/input.txt');

[$rawRules, $rawUpdates] = explode("\n\n", $rawInput);

$rules = [];

foreach (explode("\n", $rawRules) as $rule) {
    [$a, $b] = explode("|", $rule);

    $rules[] = [$a, $b];
}

$updates = [];

foreach (explode("\n", $rawUpdates) as $update) {
    $updates[] = explode(",", $update);
}

$validUpdates = [];

foreach ($updates as $update) {
    $reverseUpdate = array_reverse($update);

    $ahead = [];

    foreach ($reverseUpdate as $num) {
        if (!$ahead) {
            $ahead[] = $num;

            continue;
        }

        $numRules = array_column(array_filter($rules, fn ($rule) => $rule[0] == $num), 1);

        foreach ($ahead as $inFront) {
            if (!in_array($inFront, $numRules)) {
                continue 3;
            }
        }

        $ahead[] = $num;
    }

    $validUpdates[] = $update;
}

$result = 0;

foreach ($validUpdates as $validUpdate) {
    $key = floor(count($validUpdate) / 2);

    $result += $validUpdate[$key];
}

var_dump($result);
