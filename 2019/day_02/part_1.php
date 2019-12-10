<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(',', trim($input));

$codes[1] = 12;
$codes[2] = 2;

for ($i = 0; $i < count($codes); $i+=0) {
    $opcode = $codes[$i];

    switch ($opcode) {
        case 1:
            $result = $codes[$codes[$i + 1]] + $codes[$codes[$i + 2]];
            $codes[$codes[$i + 3]] = $result;
            break;
        case 2:
            $result = $codes[$codes[$i + 1]] * $codes[$codes[$i + 2]];
            $codes[$codes[$i + 3]] = $result;
            break;
        case 99:
            break 2;
    }

    $i += 4;
}

echo $codes[0] . PHP_EOL;