<?php

$input = file_get_contents(__DIR__ . '/input.txt');
/** @var array $opcodes */
$opcodes = explode(",", $input);

$input = 5;
$output = null;

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

for ($i = 0; $i < count($opcodes); $i+=0) {
    $opcode = $opcodes[$i] % 100;

    switch ($opcode) {
        case 1:
            $result = getParamValue(getMode(1, $opcodes[$i]), $opcodes, $i + 1) + getParamValue(getMode(2, $opcodes[$i]), $opcodes, $i + 2);
            $opcodes[$opcodes[$i + 3]] = $result;
            $i += 4;
            break;
        case 2:
            $result = getParamValue(getMode(1, $opcodes[$i]), $opcodes, $i + 1) * getParamValue(getMode(2, $opcodes[$i]), $opcodes, $i + 2);
            $opcodes[$opcodes[$i + 3]] = $result;
            $i += 4;
            break;
        case 3:
            $opcodes[$opcodes[$i + 1]] = $input;
            $i += 2;
            break;
        case 4:
            $output = $opcodes[$opcodes[$i + 1]];
            $i += 2;
            break;
        case 5:
            if (getParamValue(getMode(1, $opcodes[$i]), $opcodes, $i + 1) !== 0) {
                $i = getParamValue(getMode(2, $opcodes[$i]), $opcodes, $i + 2);
                break;
            }
            $i += 3;
            break;
        case 6:
            if (getParamValue(getMode(1, $opcodes[$i]), $opcodes, $i + 1) === 0) {
                $i = getParamValue(getMode(2, $opcodes[$i]), $opcodes, $i + 2);
                break;
            }
            $i += 3;
            break;
        case 7:
            $opcodes[$opcodes[$i + 3]] = (
                getParamValue(getMode(1, $opcodes[$i]), $opcodes, $i + 1) < getParamValue(getMode(2, $opcodes[$i]), $opcodes, $i + 2)
            ) ? 1 : 0;
            $i += 4;
            break;
        case 8:
            $opcodes[$opcodes[$i + 3]] = (
                getParamValue(getMode(1, $opcodes[$i]), $opcodes, $i + 1) == getParamValue(getMode(2, $opcodes[$i]), $opcodes, $i + 2)
            ) ? 1 : 0;
            $i += 4;
            break;
        case 99:
            break 2;
    }
}

echo $output . PHP_EOL;
