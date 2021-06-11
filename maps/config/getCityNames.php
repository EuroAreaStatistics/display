<?php

//gets a list of all map codes and maps variations to this code

ini_set ('display_errors','ON');

$path = 'cities';

$shapeCode = 'ISO_A3';

$country = json_decode(file_get_contents("../json/$path/centroides.geojson"),TRUE);

$names = array();

foreach ($country['features'] as $k => $v) {

  $names[$v['properties'][$shapeCode]] = $v['properties']['CityName'];

  //if ($v['properties']['CityName'] != null) {
  //  $varnames = explode('|',$v['properties']['CityName']);
  //  foreach ($varnames as $vars) {
  //    $names[$vars] = $v['properties'][$shapeCode];
  //  }
  //}

}

print_r($names);

file_put_contents("../json/$path/codeMap.json",json_encode($names,JSON_PRETTY_PRINT));
