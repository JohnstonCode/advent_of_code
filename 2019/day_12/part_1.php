<?php

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

for ($i = 0; $i < 1000; $i++) {
    $io->updateVelocity();
    $europa->updateVelocity();
    $ganymede->updateVelocity();
    $callisto->updateVelocity();

    $io->applyVelocity();
    $europa->applyVelocity();
    $ganymede->applyVelocity();
    $callisto->applyVelocity();
}

echo $io->getEnergy() + $europa->getEnergy() + $ganymede->getEnergy() + $callisto->getEnergy() . PHP_EOL;


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
}
