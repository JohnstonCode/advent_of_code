<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(',', $input);
$computer = new Computer($codes, 0);
$screen = [];

while ($computer->isRunning()) {
    $x = $computer->run();

    if (is_null($x)) {
        break;
    }

    $y = $computer->run();
    $tile = $computer->run();

    $screen["$x,$y"] = $tile;
}

$blocks = array_filter($screen, fn ($item) => $item === 2);

echo count($blocks) . PHP_EOL;

class Computer
{
    public $codes;
    private $input;
    private $output;
    private $relativeBase;
    private $running;
    private $i;

    public function __construct(array $codes, int $input)
    {
        $this->codes = $codes;
        $this->input = $input;
        $this->output = null;
        $this->relativeBase = 0;
        $this->running = true;
        $this->i = 0;
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

    public function isRunning()
    {
        return $this->running === true;
    }

    public function run()
    {
        for (; $this->i < count($this->codes); $this->i+=0) {
            [$opcode, $param1, $param2, $param3] = $this->getParameterModes($this->codes[$this->i]);
        
            switch ($opcode) {
                case 1:
                    $result = $this->getParameterValue($param1, $this->i + 1) + $this->getParameterValue($param2, $this->i + 2);
                    $this->setValue($this->codes[$this->i + 3], $result, $param3);
                    $this->i += 4;
                    break;
                case 2:
                    $result = $this->getParameterValue($param1, $this->i + 1) * $this->getParameterValue($param2, $this->i + 2);
                    $this->setValue($this->codes[$this->i + 3], $result, $param3);
                    $this->i += 4;
                    break;
                case 3:
                    $this->setValue($this->codes[$this->i + 1], $this->input, $param1);
                    $this->i += 2;
                    break;
                case 4:
                    $this->output = $this->getParameterValue($param1, $this->i + 1);
                    $this->i += 2;
                    return $this->output;
                case 5:
                    if ($this->getParameterValue($param1, $this->i + 1) > 0) {
                        $this->i = $this->getParameterValue($param2, $this->i + 2);
                        break;
                    }

                    $this->i += 3;
                    break;
                case 6:
                    if ($this->getParameterValue($param1, $this->i + 1) === 0) {
                        $this->i = $this->getParameterValue($param2, $this->i + 2);
                        break;
                    }

                    $this->i += 3;
                    break;
                case 7:
                    $result = $this->getParameterValue($param1, $this->i + 1) < $this->getParameterValue($param2, $this->i + 2) ? 1 : 0;
                    $this->setValue($this->codes[$this->i + 3], $result, $param3);

                    $this->i += 4;
                    break;
                
                    break;
                case 8:
                    $result = $this->getParameterValue($param1, $this->i + 1) === $this->getParameterValue($param2, $this->i + 2) ? 1 : 0;
                    $this->setValue($this->codes[$this->i + 3], $result, $param3);

                    $this->i += 4;
                    break;
                case 9:
                    $this->relativeBase += $this->getParameterValue($param1, $this->i + 1);
                    $this->i += 2;
                    break;
                case 99:
                    $this->running = false;
                    break 2;
                default:
                    var_dump($this->i);
                    var_dump($this->codes[$this->i]);
                    var_dump([$opcode, $param1, $param2, $param3]);
                    die();
            }
        }
    }
}
