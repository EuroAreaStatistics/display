<?php

// convert precision of JSON numbers for map files

ini_set('precision', '4');

$lines = preg_split("/=/", file_get_contents("worldECB-max.js"), 2);

$json= json_decode($lines[1]);

echo 'Last error: ', json_last_error();

file_put_contents("worldECB.js", $lines[0] . '=' . json_encode($json));

unset($lines);
