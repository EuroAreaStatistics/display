<?php

//gets a list of all map codes and maps variations to this code

ini_set ('display_errors','ON');

$path = 'countries/BWA';

$shapeCode = 'NAME_1';

$country = json_decode(file_get_contents("../json/$path/shapes.geojson"),TRUE);

$names = array();

foreach ($country['features'] as $k => $v) {

  $names[$v['properties']['NAME_1']] = $v['properties']['NAME_1'];

  if ($v['properties']['VARNAME_1'] != null) {
    $varnames = explode('|',$v['properties']['VARNAME_1']);
    foreach ($varnames as $vars) {
      $names[$vars] = $v['properties']['NAME_1'];
    }
  }

}

print_r($names);

file_put_contents("../json/$path/codeMap.json",json_encode($names,JSON_PRETTY_PRINT));
