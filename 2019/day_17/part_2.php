<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(',', $input);
$computer = new Computer($codes, 0);
$map = [];
$x = 0;
$y = 0;
$rX = 0;
$rY = 0;

while (true) {
    $output = $computer->run();
    if (!$output) {
        break;
    }

    switch ($output) {
        case 35:
        case 46:
            $map[$y][$x] = chr($output);
            $x++;
            break;
        case 10:
            $y++;
            $x = 0;
            break;
        default:
            $rX = $x;
            $rY = $y;
            $map[$y][$x] = chr($output);
            $x++;
    }
}

$dir = 'U';
$turns = ['U' => ['L', 'R'], 'L' => ['D', 'U'], 'R' => ['U', 'D'], 'D' => ['R', 'L']];
$deltaX = ['U' => 0, 'L' => -1, 'R' => 1, 'D' => 0];
$deltaY = ['U' => -1, 'L' => 0, 'R' => 0, 'D' => 1];
$steps = [];
$moves = 0;
$lastTurn = '';

while (true) {
    $next = $map[$rY + $deltaY[$dir]][$rX + $deltaX[$dir]] ?? '';
    if ($next === '#') {
        $moves++;
        $rY += $deltaY[$dir];
        $rX += $deltaX[$dir];
        continue;
    }
    foreach ($turns[$dir] as $turn => $newDir) {
        $next = $map[$rY + $deltaY[$newDir]][$rX + $deltaX[$newDir]] ?? '';
        if ($next === '#') {
            if ($moves) $steps[] = $lastTurn . $moves;
            $lastTurn = $turn ? 'R' : 'L';
            $dir   = $newDir;
            $moves = 1;
            $rY    += $deltaY[$dir];
            $rX    += $deltaX[$dir];
            continue 2;
        }
    }
    $steps[] = $lastTurn . $moves;
    break;
}

function combineIntoThree($steps, $level = 1, $found = [])
{
    $string = implode(',', $steps);
    $stepsCount = count($steps);
    $subSteps = [];
    foreach ($steps as $step) {
        $subStepsCount = count($subSteps) + 1;
        if ($step === '0') {
            if ($subStepsCount === 2) break;
            $subSteps = [];
            continue;
        }
        $subSteps[] = $step;
        if ($subStepsCount < 2) continue;
        $substr2 = implode(',', $subSteps);
        if (substr_count($string, $substr2) === 1) break;
        $stepsCopy = $steps;
        for ($i = 0, $to = $stepsCount - $subStepsCount; $i <= $to; $i++) {
            foreach ($subSteps as $j => $subStep) {
                if ($steps[$i + $j] !== $subStep) continue 2;
            }
            foreach ($subSteps as $j => $subStep) {
                $stepsCopy[$i + $j] = '0';
            }
            $i += $subStepsCount -1;
        }
        if ($level < 3) {
            $foundCopy = $found;
            $foundCopy[] = $substr2;
            if ($result = combineIntoThree($stepsCopy, $level + 1, $foundCopy)) {
                return $result;
            }
        } elseif ($level === 3) {
            if (!count(array_diff($stepsCopy, ['0']))) {
                $found[] = $substr2;
                return $found;
            }
        }
    }
    return null;
}

$subCombinations = combineIntoThree($steps);
$routine = implode(',', $steps);
foreach ($subCombinations as $index => $subCombination) {
    $routine = str_replace($subCombination, chr(ord('A') + $index), $routine);
}

$codes[0] = 2;
$comp = new Computer($codes);
$input = [];
foreach (str_split($routine) as $char) {
    $comp->input(ord($char));
}

$comp->input(10);

foreach ($subCombinations as $subCombination) {
    foreach (str_split($subCombination) as $char) {
        $comp->input(ord($char));
        if ($char === 'L' || $char === 'R') $comp->input(ord(','));
    }
    $comp->input(10);
}

$comp->input(ord('n'));
$comp->input(10);

while ($output = $comp->run()) {
    echo $output . PHP_EOL;
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
