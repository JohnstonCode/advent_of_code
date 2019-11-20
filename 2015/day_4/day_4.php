<?php

$secret = 'yzbqklnj';

for ($i = 0; $i < PHP_INT_MAX; $i++) {
    $hash = md5($secret . $i);
    if (strpos($hash, '00000') === 0) {
        echo $hash . PHP_EOL;
        break;
    }
}

echo $i . PHP_EOL;

for ($i = 0; $i < PHP_INT_MAX; $i++) {
    $hash = md5($secret . $i);
    if (strpos($hash, '000000') === 0) {
        echo $hash . PHP_EOL;
        break;
    }
}

echo $i . PHP_EOL;
