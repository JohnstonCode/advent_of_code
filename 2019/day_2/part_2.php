<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(',', trim($input));

for ($verb = 0; $verb < 100; $verb++) {
    for ($noun = 0; $noun < 100; $noun++) {
        $codes = explode(',', trim($input));
        $codes[1] = $noun;
        $codes[2] = $verb;

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

            if ($codes[0] === 19690720) {
                echo (100 * $noun + $verb) . PHP_EOL;
                break 3; 
            }
        
            $i += 4;
        }
    }
}
