<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(',', $input);

$y = 500;
$lastEdge = 0;
$started = false;

while ($y < 2000) {
    $started = false;
    for ($x = $lastEdge; $x < 2000; $x++) {
        $inBeam = inBeam($x, $y);

        if ($inBeam && !$started) {
            $started = true;
        } elseif (!$inBeam && $started) {
            $lastEdge = $x - 1;
            break;
        }
    }

    if (inBeam($lastEdge - 99, $y + 99)) {
        echo ($lastEdge - 99) * 10000 + $y .PHP_EOL;
        break;
    }

    $y += 1;
}

function inBeam(int $x, int $y) {
    global $codes;
    $computer = new Computer($codes, [$x, $y]);

    return $computer->run() === 1;
}

class Computer
{
    public $codes;
    private $inputs;
    private $output;
    private $relativeBase;

    public function __construct(array $codes, array $inputs)
    {
        $this->codes = $codes;
        $this->inputs = $inputs;
        $this->output = null;
        $this->relativeBase = 0;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getParameterModes($code)
    {
        $code = str_pad($code, 5, '0', STR_PAD_LEFT);
        $opcode = (int) substr($code, 3, 2);
        $param1 = (int) substr($code, 2, 1);
        $param2 = (int) substr($code, 1, 1);
        $param3 = (int) substr($code, 0, 1);

        return [$opcode, $param1, $param2, $param3];
    }

    public function getParameterValue(int $mode, int $position): int
    {
        switch ($mode) {
            case 0:
                return $this->codes[$this->codes[$position]] ?? 0;
            case 1:
                return $this->codes[$position] ?? 0;
            case 2:
                $memoryAddress = $this->relativeBase + $this->codes[$position];
                return $this->codes[$memoryAddress] ?? 0;
        }
    }

    public function setValue(int $index, int $value, int $mode)
    {
        if ($mode === 0) {
            $this->codes[$index] = $value;
        } else if ($mode === 2) {
            $this->codes[$this->relativeBase + $index] = $value;
        }
    }

    public function run()
    {
        for ($i = 0; $i < count($this->codes); $i+=0) {
            [$opcode, $param1, $param2, $param3] = $this->getParameterModes($this->codes[$i]);

            switch ($opcode) {
                case 1:
                    $result = $this->getParameterValue($param1, $i + 1) + $this->getParameterValue($param2, $i + 2);
                    $this->setValue($this->codes[$i + 3], $result, $param3);
                    $i += 4;
                    break;
                case 2:
                    $result = $this->getParameterValue($param1, $i + 1) * $this->getParameterValue($param2, $i + 2);
                    $this->setValue($this->codes[$i + 3], $result, $param3);
                    $i += 4;
                    break;
                case 3:
                    $this->setValue($this->codes[$i + 1], array_shift($this->inputs), $param1);
                    $i += 2;
                    break;
                case 4:
                    $this->output = $this->getParameterValue($param1, $i + 1);
                    $i += 2;

                    return $this->output;
                case 5:
                    if ($this->getParameterValue($param1, $i + 1) > 0) {
                        $i = $this->getParameterValue($param2, $i + 2);
                        break;
                    }

                    $i += 3;
                    break;
                case 6:
                    if ($this->getParameterValue($param1, $i + 1) === 0) {
                        $i = $this->getParameterValue($param2, $i + 2);
                        break;
                    }

                    $i += 3;
                    break;
                case 7:
                    $result = $this->getParameterValue($param1, $i + 1) < $this->getParameterValue($param2, $i + 2) ? 1 : 0;
                    $this->setValue($this->codes[$i + 3], $result, $param3);

                    $i += 4;
                    break;

                    break;
                case 8:
                    $result = $this->getParameterValue($param1, $i + 1) === $this->getParameterValue($param2, $i + 2) ? 1 : 0;
                    $this->setValue($this->codes[$i + 3], $result, $param3);

                    $i += 4;
                    break;
                case 9:
                    $this->relativeBase += $this->getParameterValue($param1, $i + 1);
                    $i += 2;
                    break;
                case 99:
                    break 2;
                default:
                    var_dump($i);
                    var_dump($this->codes[$i]);
                    var_dump([$opcode, $param1, $param2, $param3]);
                    die();
            }
        }
    }
}
