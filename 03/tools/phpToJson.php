<?php

$dir = __DIR__.'/../chartTypes/mapChartWizard';

$variable = 'colors';

include ($dir.'/'.$variable.'.php');

file_put_contents($dir.'/'.$variable.'.json', json_encode($colors,JSON_PRETTY_PRINT) );
