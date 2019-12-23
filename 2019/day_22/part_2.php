<?php

const DECK_SIZE = 119315717514047;
const ITERATIONS = 101741582076661;
const CARD_POSITION = 2020;

$input = file_get_contents(__DIR__ . '/input.txt');

$a = 1;
$b = 0;

foreach (array_reverse(explode("\n", $input)) as $line) {
    if (preg_match('/^cut/', $line)) {
        $num = str_replace('cut ', '', $line);
        $b += $num;
    } elseif (preg_match('/^deal with increment/', $line)) {
        $num = str_replace('deal with increment ', '', $line);
        $p = pow($num, DECK_SIZE - 2) % DECK_SIZE;
        $a *= $p;
        $b *= $p;
    } elseif (preg_match('/^deal into new stack/', $line)) {
        $b += 1;
        $a *= -1;
        $b *= -1;
    }

    $a %= DECK_SIZE;
    $b %= DECK_SIZE;
}

echo ((pow($a, ITERATIONS) % DECK_SIZE) * CARD_POSITION + $b * ((pow($a, ITERATIONS) % DECK_SIZE) +DECK_SIZE- 1) * (pow($a-1, DECK_SIZE - 2) % DECK_SIZE) ) % DECK_SIZE . PHP_EOL;
