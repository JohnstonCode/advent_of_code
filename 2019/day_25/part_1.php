<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(',', $input);
$comp = new Computer($codes);

$buffer = '';

$commands = ['north', 'south', 'west', 'east', 'south', 'take spool of cat6', 'west', 'take space heater', 'north', 'take weather machine', 'north', 'west', 'west', 'take whirled peas', 'east', 'east', 'south', 'south', 'south', 'take shell', 'north', 'east', 'east', 'west', 'north', 'north', 'south', 'west', 'east', 'south', 'west', 'south', 'north', 'east', 'east', 'west', 'inv', 'north', 'west', 'east', 'north', 'south', 'south', 'west', 'south', 'north', 'east', 'north', 'west', 'east', 'south', 'east', 'south', 'take hypercube', 'south', 'north', 'north', 'west', 'west', 'south', 'north', 'east', 'north', 'west', 'east', 'south', 'west', 'north', 'west', 'south', 'east', 'take candy cane', 'west', 'south', 'take space law space brochure', 'north', 'east', 'west', 'north', 'east', 'south', 'east', 'east', 'south', 'south', 'south'];
$items = ['spool of cat6', 'space heater', 'weather machine', 'whirled peas', 'hypercube', 'space law space brochure', 'candy cane', 'shell'];

foreach ($items as $item) {
    $commands[] = "drop $item";
}

for ($i = 0; $i < 2**count($items); $i++) {
    $set = [];
    for ($j = 0; $j < count($items); $j++) {
        if (($i>>$j)&1 === 1) {
            $set[] = $items[$j];
        }
    }

    foreach ($set as $item) {
        $commands[] = "take $item";
    }
    $commands[] = 'east';
    foreach ($set as $item) {
        $commands[] = "drop $item";
    }
}

while (true) {
    $output = chr($comp->run());
    $buffer .= $output;
    if (strpos($buffer, 'Command?') !== false) {
        foreach (str_split(array_shift($commands)) as $char) {
            $comp->input(ord($char));
        }
        $comp->input(10);
        $buffer = '';
    }

    if (strpos($buffer, 'main airlock.') !== false) {
        echo $buffer . PHP_EOL;
        break;
    }
}


class Computer
{
    public $codes;
    private $input = [];
    private $output;
    private $relativeBase;
    private $i = 0;

    public function __construct(array $codes, ...$input)
    {
        $this->codes = $codes;
        $this->output = null;
        $this->relativeBase = 0;
        array_push($this->input, ...$input);
    }

    public function input($input)
    {
        $this->input[] = $input;
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
                    $this->setValue($this->codes[$this->i + 1], array_shift($this->input), $param1);
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
