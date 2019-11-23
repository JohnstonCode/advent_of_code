<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$arr = explode("\n", $input);

$operations = [
    'AND' => '&',
    'OR' => '|',
    'LSHIFT' => '<<',
    'RSHIFT' => '>>',
    'NOT' => '~',
];

$wires = [];

while (!isset($wires['a'])) {
    foreach ($arr as $key => $line) {
        $wires['b'] = 956;
        if (preg_match_all('/^(\d+)\s->\s(\w+)/', $line, $matches)) {
            [, $signal, $wire] = flatten($matches);
            
            $wires[$wire] = (int) $signal;
            unset($arr[$key]);
            continue;
        }

        if (preg_match_all('/^(\w+)\s->\s(\w+)/', $line, $matches)) {
            [, $firstWire, $secondWire] = flatten($matches);

            if (!isset($wires[$firstWire])) {
                continue;
            }

            $wires[$secondWire] = $wires[$firstWire];
            unset($arr[$key]);
            continue;
        }

        if (preg_match_all('/^(\d+)\s(AND|OR)\s(\w+)\s->\s(\w+)/', $line, $matches)) {
            [, $first, $op, $second, $wire] = flatten($matches);

            if (!isset($wires[$second])) {
                continue;
            }

            $wires[$wire] = eval('return ' . $first . ' ' . $operations[$op] . ' ' . $wires[$second] . ';');
            unset($arr[$key]);
            continue;
        }

        if (preg_match_all('/^(\w+)\s(AND|OR)\s(\w+)\s->\s(\w+)/', $line, $matches)) {
            [, $first, $op, $second, $wire] = flatten($matches);

            if (!isset($wires[$first], $wires[$second])) {
                continue;
            }

            $wires[$wire] = eval('return ' . $wires[$first] . ' ' . $operations[$op] . ' ' . $wires[$second] . ';');
            unset($arr[$key]);
            continue;
        }

        if (preg_match_all('/^(\w+)\s(LSHIFT|RSHIFT)\s(\d+)\s->\s(\w+)/', $line, $matches)) {
            [, $fromWire, $op, $shift, $toWire] = flatten($matches);

            if (!isset($wires[$fromWire])) {
                continue;
            }

            $wires[$toWire] = eval('return ' . $wires[$fromWire] . ' ' . $operations[$op] . ' ' . $shift . ';');
            unset($arr[$key]);
            continue;
        }

        if (preg_match_all('/^(NOT)\s(\w+)\s->\s(\w+)/', $line, $matches)) {
            [, $op, $fromWire, $toWire] = flatten($matches);

            if (!isset($wires[$fromWire])) {
                continue;
            }

            $wires[$toWire] = (~ $wires[$fromWire]) & 0xFFFF;

            unset($arr[$key]);
            continue;
        }
    }

    // echo count($wires) . PHP_EOL;

    // if (count($wires) === 338) {
    //     break;
    // }
}

function flatten(array $array) {
    return array_merge(...array_values($array));
}

// var_dump($wires);
// die();

echo $wires['a'] . PHP_EOL;
