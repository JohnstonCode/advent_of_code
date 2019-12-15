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

function getRequiredOres($fuel) {
    $ores = [];

    $reaction = function ($chemical, $amount) use (&$reaction) {
        global $ores;
        global $reactions;
        $ore = 0;
        $needed = ceil($amount / $reactions[$chemical]['amount']);
        foreach ($reactions[$chemical]['inputs'] as $input) {
            $newAmount = $input['amount'] * $needed;
            if ($input['chemical'] === 'ORE') {
                $ore += $newAmount;
            } else {
                $ores[$input['chemical']] = $ores[$input['chemical']] ?? 0;
                if ($ores[$input['chemical']] < $newAmount) {
                    $ore += $reaction($input['chemical'], $newAmount - $ores[$input['chemical']]);
                }

                $ores[$input['chemical']] = $ores[$input['chemical']] - $newAmount;
            }
        }

        $ores[$chemical] = ($ores[$chemical] ?? 0) + ($needed * $reactions[$chemical]['amount']);

        return $ore;
    };

    return $reaction('FUEL', $fuel);
}

function calculateMaximumFuel($startFuel = 1000000) {
    global $ores;
    $ore = 0;
    $previousOre = 0;
    $fuel = $startFuel;
    $i = $startFuel;
    $target = 1e12;

    while (true) {
        $previousOre = $ore;
        $ore = getRequiredOres($fuel);

        if ($previousOre >= $target && $ore <= $target && (int)$i === 1) {
            break;
        }

        if ($ore < $target) {
            if ($ore - $previousOre > $previousOre) {
                $i *= 2;
            }

            $fuel += $i;
        } else {
            $i = ceil($i / 2);
            $fuel -= $i;
        }
    }

    return $fuel;
}

echo calculateMaximumFuel() . PHP_EOL;
