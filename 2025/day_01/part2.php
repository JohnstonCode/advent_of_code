<?php

$content = file_get_contents(__DIR__ . '/input.txt');

class Rotation {
    public function __construct(
        public readonly string $dir,
        public readonly int $amount,
    ) {
    }
}

$rotations = [];

foreach(explode("\n", trim($content)) as $line) {
    $dir = $line[0];
    $count = substr($line, 1);

    $rotations[] = new Rotation($dir, (int) $count);
}

$pos = 50;
$result = 0;

/** @var Rotation $rotation */
foreach($rotations as $rotation) {
    if ($rotation->dir === 'R') {
        for($i = 0; $i < $rotation->amount; $i++) {
            $np = $pos + 1;

            if ($np === 0) {
                $result++;
            }

            if ($np > 99) {
                $pos = 0;

                $result++;

                continue;
            }

            $pos = $np;
        }
    } else {
        for($i = 0; $i < $rotation->amount; $i++) {
            $np = $pos - 1;

            if ($np === 0) {
                $result++;
            }

            if ($np < 0) {
                $pos = 99;

                continue;
            }

            $pos = $np;
        }
    }
}

var_dump($result);

