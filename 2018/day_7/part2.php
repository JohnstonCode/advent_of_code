<?php

$input = file(__DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$steps = [];

foreach ($input as $line) {
    preg_match_all('/\s([A-Z])\s/', $line, $matches);

    $steps[$matches[1][1]][] = $matches[1][0];

    if (!isset($steps[$matches[1][0]])) {
        $steps[$matches[1][0]] = [];
    }
}

$workers = [];
$queue = [];
$time = 0;

while (true) {
    foreach ($workers as $key => $val) {
        $workers[$key]--;

        if ($workers[$key] === 0) {
            $t = $time - 1;
            echo "removing $key at $t\n";
            unset($workers[$key]);

            foreach ($steps as $step => &$value) {
                if (in_array($key, $value)) {
                    $keyToRemove = array_keys($value, $key)[0];
                    unset($value[$keyToRemove]);
                }
            }
        }
    }

    while (count($workers) < 5) {
        $currentSteps = [];

        array_walk($steps, function($val, $key) use (&$currentSteps) {
            if (count($val) === 0) {
                $currentSteps[] = $key;
            }
        });

        if (!count($currentSteps)) {
            break;
        }

        $currentStep = $currentSteps[0];

        unset($steps[$currentStep]);
        echo "Adding $currentStep to worker at $time\n";
        $workers[$currentStep] = ord($currentStep) - 64 + 60;
    }
    
    if (empty($workers)) {
        break;
    }

    $time++;
}

echo $time . "\n";

