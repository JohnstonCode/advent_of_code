<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$opcodes = explode(",", $input);

$opcodes[1] = 12;
$opcodes[2] = 2;

$i = 0;

while (true) {
    switch ($opcodes[$i]) {
        case 1:
            $result = $opcodes[$opcodes[$i + 1]] + $opcodes[$opcodes[$i + 2]];
            $opcodes[$opcodes[$i + 3]] = $result;
            break;
        case 2:
            $result = $opcodes[$opcodes[$i + 1]] * $opcodes[$opcodes[$i + 2]];
            $opcodes[$opcodes[$i + 3]] = $result;
            break;
        case 99:
            break 2;
    }

    $i += 4;
}

echo $opcodes[0] . PHP_EOL;

$noun;
$verb;

for ($noun = 0; $noun <= 99; $noun++) {
    for ($verb = 0; $verb <= 99; $verb++) {
        $opcodeCopy = explode(",", $input);
        $opcodeCopy[1] = $noun;
        $opcodeCopy[2] = $verb;
        $i = 0;

        while (true) {
            switch ($opcodeCopy[$i]) {
                case 1:
                    $result = $opcodeCopy[$opcodeCopy[$i + 1]] + $opcodeCopy[$opcodeCopy[$i + 2]];
                    $opcodeCopy[$opcodeCopy[$i + 3]] = $result;
                    break;
                case 2:
                    $result = $opcodeCopy[$opcodeCopy[$i + 1]] * $opcodeCopy[$opcodeCopy[$i + 2]];
                    $opcodeCopy[$opcodeCopy[$i + 3]] = $result;
                    break;
                case 99:
                    if ($opcodeCopy[0] === 19690720) {
                        break 4;
                    }
                    break 2;
            }

            $i += 4;
        }
    }
}

echo (100 * $noun + $verb) . PHP_EOL;
