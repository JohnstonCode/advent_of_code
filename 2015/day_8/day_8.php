<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$arr = explode("\n", $input);
$total = 0;
$newTotal = 0;

foreach ($arr as $line) {
    eval('$str = ' . $line . ';');
    $codeChars = strlen($line);
    $charMem = strlen($str);
    
    $total += $codeChars - $charMem;
    $newTotal += strlen(addslashes($line)) + 2 - $codeChars;
}

echo $total . PHP_EOL;
echo $newTotal . PHP_EOL;
