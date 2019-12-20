<?php

ini_set('memory_limit', '1G');

$input = file_get_contents(__DIR__ . '/part_2.txt');
$grid = Grid::parse($input);

echo getShortestPath($grid) . PHP_EOL;

function getShortestPathForPoint(Grid $grid, Point $start) {
    $moves = [[0,1], [0,-1], [-1, 0], [1, 0]];
    $return = [];
    $visited = [(string) $start];
    $queue = new SplQueue();
    $queue->push([0, $start, []]);

    while ($queue->count()) {
        /** @var Point $point */
        [$steps, $point, $doors] = $queue->shift();

        for ($i = 0; $i < 4; $i++) {
            [$x, $y] = $moves[$i];
            $newPoint = $point->move($x, $y);

            if (isset($visited[(string) $newPoint]) || isset($grid->walls[(string) $newPoint])) {
                continue;
            }

            $visited[(string) $newPoint] = true;

            if (isset($grid->keys[(string) $newPoint])) {
                $key = $grid->keys[(string) $newPoint];
                $return[$key] = [$steps + 1, $doors];
                $queue->push([$steps + 1, $newPoint, $doors]);
            } elseif (isset($grid->doors[(string) $newPoint])) {
                $newDoors = array_merge($doors, [$grid->doors[(string) $newPoint]]);
                $queue->push([$steps + 1, $newPoint, $newDoors]);
            } else {
                $queue->push([$steps + 1, $newPoint, $doors]);
            }
        }
    }

    return $return;
}

function getShortestPathForAllPoints(Grid $grid) {
    $return = [];

    foreach ($grid->starts as $key => $start) {
        $return[$key] = getShortestPathForPoint($grid, $start);
    }

    foreach ($grid->keys as $location => $key) {
        [$x, $y] = explode(',', $location);
        $point = new Point($x, $y);
        $return[$key] = getShortestPathForPoint($grid, $point);
    }

    return $return;
}

function getShortestPath(Grid $grid) {
    $allPaths = getShortestPathForAllPoints($grid);
    $seen_signatures = [];
    $num_keys = count($grid->keys);
    $pQueue = new SplPriorityQueue();
    $pQueue->insert([0, [0, 1, 2, 3], []], PHP_INT_MAX);

    while ($pQueue->count()) {
        [$steps, $keys, $keysFound] = $pQueue->extract();
        sort($keys);
        sort($keysFound);
        $signature = implode('', $keys) . '' . implode('', $keysFound);
        if (isset($seen_signatures[$signature])) {
            continue;
        }
        $seen_signatures[$signature] = true;

        if (count($keysFound) === $num_keys) {
            return $steps;
        }

        foreach ($keys as $index => $key) {
            $paths = $allPaths[$key];
            foreach ($paths as $destKey => $item) {
                if (in_array($destKey, $keysFound)) {
                    continue;
                }

                foreach ($item[1] as $door) {
                    if (!in_array(strtolower($door), $keysFound)) {
                        continue 2;
                    }
                }

                $newKeys = $keys;
                $newKeys[$index] = $destKey;

                $newKeysFound = array_merge($keysFound, [$destKey]);
                $pQueue->insert([$steps + $item[0], $newKeys, $newKeysFound], (PHP_INT_MAX - ($steps + $item[0])) + count($keysFound));
            }
        }
    }
}

class Grid
{
    public array $walls;
    public array $keys;
    public array $doors;
    /** @var Point[] */
    public array $starts;

    public function __construct(array $walls, array $keys, array $doors, array $starts)
    {
        $this->walls = $walls;
        $this->keys = $keys;
        $this->doors = $doors;
        $this->starts = $starts;
    }

    public static function parse(string $input)
    {
        $walls = [];
        $keys = [];
        $doors = [];
        $starts = [];

        foreach (explode("\n", $input) as $y => $line) {
            foreach (str_split(trim($line)) as $x => $char) {
                $location = new Point($x, $y);

                if ($char === '#') {
                    $walls[(string) $location] = true;
                } elseif ($char === '@') {
                    $starts[] = $location;
                } elseif (preg_match('/^[a-z]$/', $char)) {
                    $keys[(string) $location] = $char;
                } elseif (preg_match('/^[A-Z]$/', $char)) {
                    $doors[(string) $location] = $char;
                }
            }
        }

        return new Grid($walls, $keys, $doors, $starts);
    }
}

class Point
{
    public int $x;
    public int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function __toString()
    {
        return "{$this->x},{$this->y}";
    }

    public function move(int $x, int $y): Point
    {
        return new Point($this->x + $x, $this->y + $y);
    }
}
