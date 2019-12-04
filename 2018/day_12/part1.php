<?php

$input = file(__DIR__ . '/input.txt');
$state = str_split(str_replace('initial state: ', '', trim($input[0])));
$rawRules = array_slice($input, 2);
$rules = [];

foreach ($rawRules as $rawRule) {
    $rule = explode(' => ', $rawRule);

    $rules[$rule[0]] = trim($rule[1]);
}

for ($i = 0; $i < 20; $i++) {
    $start = array_search('#', $state) - 2;
    $end = array_search('#', array_reverse($state, true)) + 2;
    $newState = [];

    for ($j = $start; $j <= $end; $j++) {
        $currentState = ($state[$j - 2] ?? '.') . 
            ($state[$j -1] ?? '.') .
            ($state[$j] ?? '.') .
            ($state[$j + 1] ?? '.') . 
            ($state[$j + 2] ?? '.');

        if (isset($rules[$currentState])) {
            $newState[$j] = $rules[$currentState];
            continue;
        }
    }

    $state = $newState;
}

$sum = 0;

foreach ($state as $key => $value) {
    $sum += $value === '#' ? $key : 0;
}

echo $sum . "\n";
