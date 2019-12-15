<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$rawReactions = explode("\n", $input);


$reactions = [];

foreach ($rawReactions as $rawReaction) {
    [$input, $output] = explode(' => ', $rawReaction);
    [$outputAmount, $outputChemical] = explode(' ', $output);

    $inputs = array_map(function ($item) {
        [$amount, $chemical] = explode(' ', trim($item));

        return ['chemical' => $chemical, 'amount' => (int) $amount];
    }, explode(',', $input));

    $reactions[$outputChemical] = ['amount' => (int) $outputAmount, 'inputs' => $inputs];
}

$ores = [];

function getRequiredOres() {

    function reaction($chemical, $amount) {
        global $reactions;
        global $ores;
        $ore = 0;
        $needed = ceil($amount / $reactions[$chemical]['amount']);
        foreach ($reactions[$chemical]['inputs'] as $input) {
            $newAmount = $input['amount'] * $needed;
            if ($input['chemical'] === 'ORE') {
                $ore += $newAmount;
            } else {
                $ores[$input['chemical']] = $ores[$input['chemical']] ?? 0;
                if ($ores[$input['chemical']] < $newAmount) {
                    $ore += reaction($input['chemical'], $newAmount - $ores[$input['chemical']]);
                }

                $ores[$input['chemical']] -= $newAmount;
            }
        }

        $ores[$chemical] = ($ores[$chemical] ?? 0) + ($needed * $reactions[$chemical]['amount']);

        return $ore;
    };

    return reaction('FUEL', 1);
}

echo getRequiredOres() . PHP_EOL;

die();

$chemicals = [];
$seenFuel = false;

function getChemicalAmounts($chemical, $amount) {
    global $chemicals;
    global $reactions;

    if ($reactions[$chemical]['inputs'][0]['chemical'] === 'ORE') {
        $chemicals[$chemical] = $chemicals[$chemical] ?? 0;
        $chemicals[$chemical] += $amount;
        return;
    }

    foreach ($reactions[$chemical]['inputs'] as $input) {
        $quantity = ceil($amount / $reactions[$chemical]['amount']);
        echo "$chemical $amount $quantity {$reactions[$chemical]['amount']}" . PHP_EOL;
        for ($i = 0; $i < $quantity; $i++) {
            getChemicalAmounts($input['chemical'], $input['amount']);
        }
    }
}

getChemicalAmounts('FUEL', 1);

print_r($chemicals);
// die();

$requiredOre = 0;

foreach ($chemicals as $chemical => $amount) {
    $required = ceil($amount / $reactions[$chemical]['amount']);
    $requiredOre += $required * $reactions[$chemical]['inputs'][0]['amount'];
}

echo $requiredOre . PHP_EOL;
