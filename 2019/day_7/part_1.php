<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(",", $input);

$phaseSettings = [];

for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 5; $j++) {
        if (in_array($j, [$i])) continue;
        for ($k = 0; $k < 5; $k++) {
            if (in_array($k, [$i, $j])) continue;
            for ($l = 0; $l < 5; $l++) {
                if (in_array($l, [$i, $j, $k])) continue;
                for ($m = 0; $m < 5; $m++) {
                    if (in_array($m, [$i, $j, $k, $l])) continue;
                    $phaseSettings[] = "{$i}{$j}{$k}{$l}{$m}";
                }
            }
        }
    }
}

function getParamValue(int $mode, array $codes, int $position): int {
    if ($mode === 1) {
        return $codes[$position];
    }

    return $codes[$codes[$position]];
}

function getMode(int $param, int $opcode): int {
    $opcode = $opcode / 100;
    
    if ($param === 1 && $opcode % 10 > 0) {
        return 1;
    } else if ($param == 2 && ($opcode / 10) % 10 > 0) {
        return 1;
    }

    return 0;
}

function runAmplifier($inputSignal, $setting, $codes) {
    $codesCopy = [...$codes];
    $phase = false;
    $output = null;

    for ($i = 0; $i < count($codesCopy); $i+=0) {
        $opcode = $codesCopy[$i] % 100;
    
        switch ($opcode) {
            case 1:
                $result = getParamValue(getMode(1, $codesCopy[$i]), $codesCopy, $i + 1) + getParamValue(getMode(2, $codesCopy[$i]), $codesCopy, $i + 2);
                $codesCopy[$codesCopy[$i + 3]] = $result;
                $i += 4;
                break;
            case 2:
                $result = getParamValue(getMode(1, $codesCopy[$i]), $codesCopy, $i + 1) * getParamValue(getMode(2, $codesCopy[$i]), $codesCopy, $i + 2);
                $codesCopy[$codesCopy[$i + 3]] = $result;
                $i += 4;
                break;
            case 3:
                $input = $phase ? $inputSignal : $setting;
                $phase = true;
                $codesCopy[$codesCopy[$i + 1]] = $input;
                $i += 2;
                break;
            case 4:
                $output = $codesCopy[$codesCopy[$i + 1]];
                $i += 2;
                break;
            case 5:
                if (getParamValue(getMode(1, $codesCopy[$i]), $codesCopy, $i + 1) !== 0) {
                    $i = getParamValue(getMode(2, $codesCopy[$i]), $codesCopy, $i + 2);
                    break;
                }
                $i += 3;
                break;
            case 6:
                if (getParamValue(getMode(1, $codesCopy[$i]), $codesCopy, $i + 1) === 0) {
                    $i = getParamValue(getMode(2, $codesCopy[$i]), $codesCopy, $i + 2);
                    break;
                }
                $i += 3;
                break;
            case 7:
                $codesCopy[$codesCopy[$i + 3]] = (
                    getParamValue(getMode(1, $codesCopy[$i]), $codesCopy, $i + 1) < getParamValue(getMode(2, $codesCopy[$i]), $codesCopy, $i + 2)
                ) ? 1 : 0;
                $i += 4;
                break;
            case 8:
                $codesCopy[$codesCopy[$i + 3]] = (
                    getParamValue(getMode(1, $codesCopy[$i]), $codesCopy, $i + 1) == getParamValue(getMode(2, $codesCopy[$i]), $codesCopy, $i + 2)
                ) ? 1 : 0;
                $i += 4;
                break;
            case 99:
                break 2;
        }
    }

    return $output;
}

$max = 0;

foreach ($phaseSettings as $phaseSetting) {
    $inputSignal = 0;

    foreach (str_split($phaseSetting) as $setting) {
        $inputSignal = runAmplifier($inputSignal, $setting, $codes);
    }

    $max = max($inputSignal, $max);
}

echo $max . PHP_EOL;
