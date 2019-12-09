<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(',', $input);

$computer = new Computer($codes, 5);
$computer->run();

echo $computer->getOutput() . PHP_EOL;

class Computer
{
    public $codes;
    private $input;
    private $output;

    public function __construct(array $codes, int $input)
    {
        $this->codes = $codes;
        $this->input = $input;
        $this->output = null;
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
        if ($mode === 1) {
            return $this->codes[$position];
        }

        return $this->codes[$this->codes[$position]];
    }

    public function run()
    {
        for ($i = 0; $i < count($this->codes); $i+=0) {
            [$opcode, $param1, $param2,] = $this->getParameterModes($this->codes[$i]);
        
            switch ($opcode) {
                case 1:
                    $result = $this->getParameterValue($param1, $i + 1) + $this->getParameterValue($param2, $i + 2);
                    $this->codes[$this->codes[$i + 3]] = $result;
                    $i += 4;
                    break;
                case 2:
                    $result = $this->getParameterValue($param1, $i + 1) * $this->getParameterValue($param2, $i + 2);
                    $this->codes[$this->codes[$i + 3]] = $result;
                    $i += 4;
                    break;
                case 3:
                    $this->codes[$this->codes[$i + 1]] = $this->input;
                    $i += 2;
                    break;
                case 4:
                    $this->output = $this->codes[$this->codes[$i + 1]];
                    $i += 2;
                    break;
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
                    $this->codes[$this->codes[$i + 3]] = $this->getParameterValue($param1, $i + 1) < $this->getParameterValue($param2, $i + 2) ? 1 : 0;

                    $i += 4;
                    break;
                
                    break;
                case 8:
                    $this->codes[$this->codes[$i + 3]] = $this->getParameterValue($param1, $i + 1) === $this->getParameterValue($param2, $i + 2) ? 1 : 0;

                    $i += 4;
                    break;
                case 99:
                    break 2;
            }
        }
    }
}