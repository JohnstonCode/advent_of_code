<?php

$input = file_get_contents(__DIR__ . '/input.txt');
$codes = explode(",", $input);

$phaseSettings = [];

for ($i = 5; $i < 10; $i++) {
    for ($j = 5; $j < 10; $j++) {
        if (in_array($j, [$i])) continue;
        for ($k = 5; $k < 10; $k++) {
            if (in_array($k, [$i, $j])) continue;
            for ($l = 5; $l < 10; $l++) {
                if (in_array($l, [$i, $j, $k])) continue;
                for ($m = 5; $m < 10; $m++) {
                    if (in_array($m, [$i, $j, $k, $l])) continue;
                    $phaseSettings[] = "{$i}{$j}{$k}{$l}{$m}";
                }
            }
        }
    }
}

$max = 0;

foreach ($phaseSettings as $phaseSetting) {
    $settings = str_split($phaseSetting);

    $controllers = [
        new AmplifierController($codes, $settings[0]),
        new AmplifierController($codes, $settings[1]),
        new AmplifierController($codes, $settings[2]),
        new AmplifierController($codes, $settings[3]),
        new AmplifierController($codes, $settings[4]),
    ];

    $idx = 0;
    $inputSignal = 0;
    $result = null;

    while (true) {
        $controller = $controllers[$idx % 5];
        $controller->runAmplifier($inputSignal);
        $controllers[$idx % 5] = $controller;
        $inputSignal = $controller->getOutput();

        if ($idx === count($controllers) - 1 && $controllers[$idx]->isStopped()) {
            $result = $controllers[count($controllers) - 1]->getOutput();
            break;
        }

        $idx = ($idx + 1) % 5;
    }

    $max = max($max, $result);
}

echo $max . PHP_EOL;


class AmplifierController
{
    private $codes;
    private $phaseSetting;
    private $isStopped;
    private $output;
    private $hasSetPhase;
    private $i;

    public function __construct(array $codes, int $phaseSetting)
    {
        $this->codes = $codes;
        $this->phaseSetting = $phaseSetting;
        $this->isStopped = false;
        $this->output = null;
        $this->hasSetPhase = false;
        $this->i = 0;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function isStopped()
    {
        return $this->isStopped === true;
    }

    public function runAmplifier($inputSignal) {
        if ($this->isStopped()) {
            return;
        }
    
        for (; $this->i < count($this->codes); $this->i+=0) {
            $opcode = $this->codes[$this->i] % 100;
        
            switch ($opcode) {
                case 1:
                    $result = $this->getParamValue($this->getMode(1, $this->codes[$this->i]), $this->i + 1) + $this->getParamValue($this->getMode(2, $this->codes[$this->i]), $this->i + 2);
                    $this->codes[$this->codes[$this->i + 3]] = $result;
                    $this->i += 4;
                    break;
                case 2:
                    $result = $this->getParamValue($this->getMode(1, $this->codes[$this->i]), $this->i + 1) * $this->getParamValue($this->getMode(2, $this->codes[$this->i]), $this->i + 2);
                    $this->codes[$this->codes[$this->i + 3]] = $result;
                    $this->i += 4;
                    break;
                case 3:
                    $input = $this->hasSetPhase ? $inputSignal : $this->phaseSetting;
                    $this->hasSetPhase = true;
                    $this->codes[$this->codes[$this->i + 1]] = $input;
                    $this->i += 2;
                    break;
                case 4:
                    $this->output = $this->codes[$this->codes[$this->i + 1]];
                    $this->i += 2;
                    $this->isStopped = false;
                    break 2;
                case 5:
                    if ($this->getParamValue($this->getMode(1, $this->codes[$this->i]), $this->i + 1) !== 0) {
                        $this->i = $this->getParamValue($this->getMode(2, $this->codes[$this->i]), $this->i + 2);
                        break;
                    }
                    $this->i += 3;
                    break;
                case 6:
                    if ($this->getParamValue($this->getMode(1, $this->codes[$this->i]), $this->i + 1) === 0) {
                        $this->i = $this->getParamValue($this->getMode(2, $this->codes[$this->i]), $this->i + 2);
                        break;
                    }
                    $this->i += 3;
                    break;
                case 7:
                    $this->codes[$this->codes[$this->i + 3]] = (
                        $this->getParamValue($this->getMode(1, $this->codes[$this->i]), $this->i + 1) < $this->getParamValue($this->getMode(2, $this->codes[$this->i]), $this->i + 2)
                    ) ? 1 : 0;
                    $this->i += 4;
                    break;
                case 8:
                    $this->codes[$this->codes[$this->i + 3]] = (
                        $this->getParamValue($this->getMode(1, $this->codes[$this->i]), $this->i + 1) == $this->getParamValue($this->getMode(2, $this->codes[$this->i]), $this->i + 2)
                    ) ? 1 : 0;
                    $this->i += 4;
                    break;
                case 99:
                    $this->isStopped = true;
                    break 2;
            }
        }
    }

    private function getParamValue(int $mode, int $position): int {
        if ($mode === 1) {
            return $this->codes[$position];
        }
    
        return $this->codes[$this->codes[$position]];
    }

    private function getMode(int $param, int $opcode): int {
        $opcode = $opcode / 100;
        
        if ($param === 1 && $opcode % 10 > 0) {
            return 1;
        } else if ($param == 2 && ($opcode / 10) % 10 > 0) {
            return 1;
        }
    
        return 0;
    }
}
