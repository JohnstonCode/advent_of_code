<?php

$password = 125730;
$max = 579381;
$passwordCount = 0;
$newPasswordCount = 0;

function passwordHasDouble(int $password) {
    $passwordArray = str_split((string) $password);

    for ($i = 0; $i < count($passwordArray); $i++) {
        if (!isset($passwordArray[$i + 1])) {
            continue;
        }

        if ($passwordArray[$i] === $passwordArray[$i + 1]) {
            return true;
        }
    }

    return false;
}

function passwordHasOneLargeDouble(int $password) {
    $passwordArray = str_split((string) $password);
    $doubles = [];

    for ($i = 0; $i < count($passwordArray); $i++) {
        if (!isset($passwordArray[$i + 1])) {
            continue;
        }

        if ($passwordArray[$i] === $passwordArray[$i + 1]) {
            if (!isset($doubles[$passwordArray[$i]])) {
                $doubles[$passwordArray[$i]] = 0;
            }

            $doubles[$passwordArray[$i]]++;
        }
    }

    asort($doubles);

    if (count($doubles) > 0 && array_shift($doubles) === 1) {
        return true;
    }

    return false;
}

function passwordDoesntDecrease(int $password) {
    $passwordArray = str_split((string) $password);

    for ($i = 0; $i < count($passwordArray); $i++) {
        if (!isset($passwordArray[$i + 1])) {
            continue;
        }

        if ($passwordArray[$i + 1] < $passwordArray[$i]) {
            return false;
        }
    }

    return true;
}


while ($password <= $max) {
    if (passwordHasDouble($password) && passwordDoesntDecrease($password)) {
        $passwordCount++;
    }

    if (passwordHasOneLargeDouble($password) && passwordDoesntDecrease($password)) {
        $newPasswordCount++;
    }

    $password++;
}


echo $passwordCount . PHP_EOL;
echo $newPasswordCount . PHP_EOL;
