<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$input = explode("\n", $input);
$maze = [];
$portals = [];

foreach ($input as $y => $line) {
    foreach (str_split($line) as $x => $char) {
        $maze[$y][$x] = $char;

        if (!ctype_upper($char)) {
            continue;
        }

        //Top
        if ($y === 0) {
            $key = $char . $input[$y+1][$x];
            $portals[$key] = $portals[$key] ?? [];
            $portals[$key][] = $x . ',' . ($y + 2);
            continue;
        }

        //Right
        if ($x === (strlen($input[0]) - 1)) {
            $key = $input[$y][$x-1] . $char;
            $portals[$key] = $portals[$key] ?? [];
            $portals[$key][] = ($x-2) . ',' . $y;
            continue;
        }

        // Inner bottom
        if (ctype_upper(($input[$y+1][$x] ?? '')) && isset($input[$y+2][$x]) && $input[$y+2][$x] === '.') {
            $key = $char . $input[$y+1][$x];
            $portals[$key] = $portals[$key] ?? [];
            $portals[$key][] = $x . ',' . ($y + 2);
            continue;
        }

        //Left
        if ($x === 0) {
            $key = "$char{$input[$y][$x + 1]}";
            $portals[$key] = $portals[$key] ?? [];
            $portals[$key][] = ($x + 2) . ",$y";
            continue;
        }

        //Inner top
        if (ctype_upper(($input[$y + 1][$x] ?? '')) && isset($input[$y - 1][$x]) && $input[$y - 1][$x] === '.' && isset($input[$y+2][$x])) {
            $key = "$char" . $input[$y + 1][$x];
            $portals[$key] = $portals[$key] ?? [];
            $portals[$key][] = $x . ',' . ($y - 1);
            continue;
        }

        //Inner right
        if (ctype_upper(($input[$y][$x + 1] ?? '')) && isset($input[$y][$x + 2]) && $input[$y][$x + 2] === '.') {
            $key = $char . $input[$y][$x+1];
            $portals[$key] = $portals[$key] ?? [];
            $portals[$key][] = ($x + 2) . ',' . $y;
            continue;
        }

        //Inner left
        if (ctype_upper(($input[$y][$x + 1] ?? '')) && !empty($input[$y][$x + 2])) {
            $key = $char . $input[$y][$x+1];
            $portals[$key] = $portals[$key] ?? [];
            $portals[$key][] = ($x - 1) . ',' . $y;
            continue;
        }

        //Bottom
        if ($y === count($input) - 1) {
            $key = $input[$y -1][$x] . $char;
            $portals[$key] = $portals[$key] ?? [];
            $portals[$key][] = $x . ',' . ($y - 2);
            continue;
        }
    }
}

$startPos = $portals['AA'][0];
$endPos = $portals['ZZ'][0];
$queue = new SplQueue();
$queue->push([$startPos, 0]);
$visited = [];
$moves = [[0,1], [0,-1], [-1, 0], [1, 0]];

while ($queue->count()) {
    [$pos, $steps] = $queue->shift();
    [$x, $y] = explode(',', $pos);


    for ($i = 0; $i < count($moves); $i++) {
        [$X, $Y] = $moves[$i];
        $newX = $x + $X;
        $newY = $y + $Y;

        if (isset($visited["$newX,$newY"])) {
            continue;
        }

        if (!canMove($newX, $newY)) {
            continue;
        }

        if ("$newX,$newY" === $endPos) {
            echo ($steps + 1) . PHP_EOL;
            break 2;
        }

        if (isTeleport($newX, $newY)) {
            $newPos = teleport($newX, $newY);
            $queue->push([$newPos, $steps + 2]);
            continue;
        }

        $queue->push(["$newX,$newY", $steps + 1]);
    }

    $visited[$pos] = true;
}

function canMove($x, $y) {
    global $maze;

    if (($maze[$y][$x] ?? '') === '.') {
        return true;
    }

    return false;
}

function isTeleport($x, $y) {
    global $portals;

    foreach ($portals as $portal) {
        if (in_array("$x,$y", $portal)) {
            return true;
        }
    }

    return false;
}

function teleport($x, $y) {
    global $portals;

    foreach ($portals as $portal) {
        if (in_array("$x,$y", $portal)) {
            return "$x,$y" === $portal[0] ? $portal[1] : $portal[0];
        }
    }

    throw new Exception('Not a teleport ' . $x . ',' . $y);
}
