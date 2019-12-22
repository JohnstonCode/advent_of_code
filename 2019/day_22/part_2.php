<?php

const DECK_SIZE = 119315717514047;

$input = file_get_contents(__DIR__ . '/input.txt');

[$a, $b] = array_reduce(array_reverse(explode("\n", $input)), function ($carry, $line) {
    if (preg_match('/^cut/', $line)) {
        $num = str_replace('cut ', '', $line);
        return [$carry[0], (($carry[1] + $num) % DECK_SIZE + DECK_SIZE) & DECK_SIZE];
    } elseif (preg_match('/^deal with increment/', $line)) {
        $num = str_replace('deal with increment ', '', $line);
        return [modDiv($carry[0], $num, DECK_SIZE), modDiv($carry[0], $num, DECK_SIZE)];
    } elseif (preg_match('/^deal into new stack/', $line)) {
        return [(DECK_SIZE - $carry[0]) % DECK_SIZE, (DECK_SIZE + DECK_SIZE - $carry[1] - 1) % DECK_SIZE];
    }
}, [1, 0]);

$reps = 101741582076661;
$x = 2020;

while ($reps) {
    if ($reps % 2) {
        $x = (string) ((string)mulMod($x, $a, DECK_SIZE) + $b) % DECK_SIZE;
    }

    echo $x . PHP_EOL;

    [$a, $b] = [mulMod($a, $a, DECK_SIZE), (mulMod($a, $b, DECK_SIZE) + $b) % DECK_SIZE];
    $reps = floor($reps / 2);
}

echo $x . PHP_EOL;


function mulMod($a, $b, $m) {
    return ($a * $b % $m);
}

function modDiv($a, $b, $m) {
    return ($a * modInverse($b, $m)) % $m;
}

function modInverse($a, $m) {
    [$g, $x] = gcdExtended($a, $m);

    return ($x + $m) % $m;
}

function gcdExtended($a, $b) {
    $x = 0;
    $y = 1;
    $u = 1;
    $v = 0;

    while ($a !== 0) {
        $q = floor($b / $a);
        [$x, $y, $u, $v] = [$u, $v, ($x - $u * $q), ($y - $v * $q)];
        [$a, $b] = [$b % $a, $a];
    }

    return [$b, $x, $y];
}
