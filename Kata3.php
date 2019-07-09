<?php

function parse($string) {
    $zygote = [];

    foreach (array_filter(explode(PHP_EOL, $string)) as $k) {
        $zygote[] = array_map(function ($i) {
            return $i === '*';
        }, str_split($k));
    }

    return $zygote;
}

function gen($zygote) {
    $new = [];

    foreach ($zygote as $i => $row) {
        foreach ($row as $j => $cell) {
            $new[$i][$j] = alive($zygote, $i, $j);
        }
    }

    return $new;
}

function alive($zygote, $i, $j) {
    $count = 0;
    for ($k = -1; $k <= 1; $k++) {
        for ($l = -1; $l <= 1; $l++) {
            if ($k == 0 && $l == 0) {
                continue;
            }
            $count += (int)neighbor($zygote, $i + $k, $j + $l);
        }
    }

    if ($zygote[$i][$j]) {
        // Is Alive
        return $count === 2 || $count === 3;
    } else {
        // Is dead
        return $count === 3;
    }
}

function neighbor($zygote, $i, $j) {
    if (array_key_exists($i, $zygote) && array_key_exists($j, $zygote[$i])) {
        return $zygote[$i][$j];
    } else {
        return false;
    }
}

function output($zygote) {
    foreach ($zygote as $i => $row) {
        foreach ($row as $j => $cell) {
            echo ($cell ? '*' : '.');
        }
        echo PHP_EOL;
    }
    return $zygote;
}

$zygote = parse(file_get_contents(getenv('FILEPATH')));

echo 'Zygote' . PHP_EOL;
output($zygote);

for ($i = 1; $i <= 10; $i++) {
    echo 'Generation: ' . $i . PHP_EOL;
    $zygote = gen($zygote);
    output($zygote);
}
