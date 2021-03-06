<?php

$input = file_get_contents(__DIR__ . '/part_1.txt');
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
    $return['@'] = getShortestPathForPoint($grid, $grid->start);

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
    $pQueue->insert([0, '@', []], PHP_INT_MAX);

    while ($pQueue->count()) {
        [$steps, $key, $keys] = $pQueue->extract();
        sort($keys);
        $signature = "$key" . implode('', $keys);
        if (isset($seen_signatures[$signature])) {
            continue;
        }
        $seen_signatures[$signature] = true;

        if (count($keys) === $num_keys) {
            return $steps;
        }

        $paths = $allPaths[$key];
        foreach ($paths as $destKey => $item) {
            if (in_array($destKey, $keys)) {
                continue;
            }

            foreach ($item[1] as $door) {
                if (!in_array(strtolower($door), $keys)) {
                    continue 2;
                }
            }

            $newKeys = array_merge($keys, [$destKey]);
            $pQueue->insert([$steps + $item[0], $destKey, $newKeys], (PHP_INT_MAX - ($steps + $item[0])) + count($keys));
        }
    }
}

class Grid
{
    public array $walls;
    public array $keys;
    public array $doors;
    public Point $start;

    public function __construct(array $walls, array $keys, array $doors, Point $start)
    {
        $this->walls = $walls;
        $this->keys = $keys;
        $this->doors = $doors;
        $this->start = $start;
    }

    public static function parse(string $input)
    {
        $walls = [];
        $keys = [];
        $doors = [];
        $start = null;

        foreach (explode("\n", $input) as $y => $line) {
            foreach (str_split(trim($line)) as $x => $char) {
                $location = new Point($x, $y);

                if ($char === '#') {
                    $walls[(string) $location] = true;
                } elseif ($char === '@') {
                    $start = $location;
                } elseif (preg_match('/^[a-z]$/', $char)) {
                    $keys[(string) $location] = $char;
                } elseif (preg_match('/^[A-Z]$/', $char)) {
                    $doors[(string) $location] = $char;
                }
            }
        }

        return new Grid($walls, $keys, $doors, $start);
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
