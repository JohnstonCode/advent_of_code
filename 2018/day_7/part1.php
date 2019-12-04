<?php

$input = file(__DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$instructions = [];

foreach ($input as $line) {
    preg_match_all('/\s([A-Z])\s/', $line, $matches);

    $instructions[] = ['step' => $matches[1][0], 'blocking' => $matches[1][1]];

}

$incompleteSteps = array_unique(array_merge(array_column($instructions, 'step'), array_column($instructions, 'blocking')));
$completedSteps = [];

$test = [];

foreach ($incompleteSteps as $incompleteStep) {
    $blockedBy = array_keys(array_column($instructions, 'blocking'), $incompleteStep);
    $test[$incompleteStep] = [];
    
    foreach ($blockedBy as $key) {
        $test[$incompleteStep][] = $instructions[$key]['step'];
    }
}

ksort($test);

$steps = [];

while (count($test)) {
    foreach ($test as $letter => $step) {
        if (!count($step)) {
            $completedSteps[$letter] = true;
            unset($test[$letter]);

            foreach ($test as &$step1) {
                if (($key = array_search($letter, $step1)) !== false) {
                    unset($step1[$key]);
                    $step1 = array_values($step1);
                }
            }

            break;
        }
    }
}

echo implode('', array_keys($completedSteps)) . "\n";
