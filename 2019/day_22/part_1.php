<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$deck = range(0, 10006);

foreach (explode("\n", $input) as $line) {
    if (preg_match('/^cut/', $line)) {
        $num = str_replace('cut ', '', $line);
        $deck = cut($deck, (int) $num);
    } elseif (preg_match('/^deal with increment/', $line)) {
        $num = str_replace('deal with increment ', '', $line);
        $deck = dealWithIncrement($deck, $num);
    } elseif (preg_match('/^deal into new stack/', $line)) {
        $deck = dealNewStack($deck);
    }
}

echo array_search('2019', $deck) . PHP_EOL;

function dealNewStack(array $deck): array {
    $reverse = array_reverse($deck);

    return array_values($reverse);
}

function cut(array $deck, int $num): array {
    $cut = array_splice($deck, 0, $num);
    $deck = array_merge($deck, $cut);

    return array_values($deck);
}

function dealWithIncrement(array $deck, int $increment): array {
    $newDeck = array_fill(0, count($deck), null);

    for ($i = 0; $i < count($deck); $i++) {
        $newDeck[($i * $increment) % count($deck)] = $deck[$i];
    }

    return $newDeck;
}
