<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$words = explode(" ", $input);

$players = $words[0];
$maxMarble = $words[6];
$circle = [0];
$currentPlayer = 1;
$playerScores = [];

$list = new LinkedList(0, null, null);

$list->next = $list;
$list->prev = $list;

$currentIndex = $list;

for ($i = 1, $len = $maxMarble; $i <= $len; $i++) {
    if (($i % 23) === 0) {
        $linkToRemove = $currentIndex->prev->prev->prev->prev->prev->prev->prev;

        $prev = $linkToRemove->prev;
        $next = $linkToRemove->next;

        $prev->next = $next;
        $next->prev = $prev;

        $currentIndex = $next;

        $playerScores[$currentPlayer] = ($playerScores[$currentPlayer] ?? 0) + ($linkToRemove->val + $i);
    } else {
        $prev = $currentIndex->next;
        $next = $currentIndex->next->next;

        $link = new LinkedList($i, $next, $prev);

        $prev->next = $link;
        $next->prev = $link;

        $currentIndex = $link;
    }

    $currentPlayer = $currentPlayer + 1 > $players ? 1 : $currentPlayer + 1;
}

echo max($playerScores) . "\n";

class LinkedList
{
    public $next;
    public $val;
    public $prev;

    public function __construct($val, $next, $prev)
    {
        $this->val = $val;
        $this->prev = $prev;
        $this->next = $next;
    }
}
