<?php
ini_set('memory_limit', '400M');

$input = file_get_contents(__DIR__ . '/input.txt');
preg_match_all('/^<x=(-?\d+), y=(-?\d+), z=(-?\d+)>$/m', $input, $moons);

$io = new Moon($moons[1][0], $moons[2][0], $moons[3][0]);
$europa = new Moon($moons[1][1], $moons[2][1], $moons[3][1]);
$ganymede = new Moon($moons[1][2], $moons[2][2], $moons[3][2]);
$callisto = new Moon($moons[1][3], $moons[2][3], $moons[3][3]);

$io->setMoons([$europa, $ganymede, $callisto]);
$europa->setMoons([$io, $ganymede, $callisto]);
$ganymede->setMoons([$io, $europa, $callisto]);
$callisto->setMoons([$io, $europa, $ganymede]);

$i = 0;
$seen = ['x' => [], 'y' => [], 'z' => []];
$repeats = [];

while (count($repeats) < 3) {
    $io->updateVelocity();
    $europa->updateVelocity();
    $ganymede->updateVelocity();
    $callisto->updateVelocity();

    $io->applyVelocity();
    $europa->applyVelocity();
    $ganymede->applyVelocity();
    $callisto->applyVelocity();

    [$ioX, $ioY, $ioZ] = $io->getSignatures();
    [$europaX, $europaY, $europaZ] = $europa->getSignatures();
    [$ganymedeX, $ganymedeY, $ganymedeZ] = $ganymede->getSignatures();
    [$callistoX, $callistoY, $callistoZ] = $callisto->getSignatures();

    if (!isset($repeats['x'])) {
        $signature = implode(',', [$ioX, $europaX, $ganymedeX, $callistoX]);
        if (isset($seen['x'][$signature])) {
            $repeats['x'] = $i;
        } else {
            $seen['x'][$signature] = true;
        }
    }

    if (!isset($repeats['y'])) {
        $signature = implode(',', [$ioY, $europaY, $ganymedeY, $callistoY]);
        if (isset($seen['y'][$signature])) {
            $repeats['y'] = $i;
        } else {
            $seen['y'][$signature] = true;
        }
    }

    if (!isset($repeats['z'])) {
        $signature = implode(',', [$ioZ, $europaZ, $ganymedeZ, $callistoZ]);
        if (isset($seen['z'][$signature])) {
            $repeats['z'] = $i;
        } else {
            $seen['z'][$signature] = true;
        }
    }

    $i++;
}

echo lcm(lcm($repeats['x'], $repeats['y']), $repeats['z']) . PHP_EOL;

class Moon
{
    private $x;
    private $y;
    private $z;
    /** @var Moon[] */
    private $moons;
    private $vX;
    private $vY;
    private $vZ;

    public function __construct(int $x, int $y, int $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->vX = 0;
        $this->vY = 0;
        $this->vZ = 0;
    }

    public function getSignatures()
    {
        return [$this->x . ',' . $this->vX, $this->y . ',' . $this->vY, $this->z . ',' . $this->vZ];
    }

    public function updateVelocity()
    {
        foreach ($this->moons as $moon) {
            $this->vX += $moon->getX() <=> $this->x;
            $this->vY += $moon->getY() <=> $this->y;
            $this->vZ += $moon->getZ() <=> $this->z;
        }
    }

    public function applyVelocity()
    {
        $this->x += $this->vX;
        $this->y += $this->vY;
        $this->z += $this->vZ;
    }

    public function getEnergy()
    {
        $pot = abs($this->x) + abs($this->y) + abs($this->z);
        $kin = abs($this->vX) + abs($this->vY) + abs($this->vZ);

        return $pot * $kin;
    }

    public function setMoons(array $moons)
    {
        $this->moons = $moons;
    }

    /**
     * @return int
     */
    public function getX() : int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY() : int
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getZ() : int
    {
        return $this->z;
    }

    /**
     * @return int
     */
    public function getVX() : int
    {
        return $this->vX;
    }

    /**
     * @return int
     */
    public function getVY() : int
    {
        return $this->vY;
    }

    /**
     * @return int
     */
    public function getVZ() : int
    {
        return $this->vZ;
    }
}

/* https://www.php.net/manual/en/ref.math.php#70969 */
function gcd($n, $m) {
    $n=abs($n); $m=abs($m);
    if ($n==0 and $m==0)
        return 1; //avoid infinite recursion
    if ($n==$m and $n>=1)
        return $n;
    return $m<$n?gcd($n-$m,$n):gcd($n,$m-$n);
}

/* https://www.php.net/manual/en/ref.math.php#70969 */
function lcm($n, $m) {
    return $m * ($n/gcd($n,$m));
}
