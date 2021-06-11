<?php

// creates centroides list from centroides shapes

ini_set ('display_errors','ON');

//$path = 'countries/BWA';
$path = 'cities';

$shapeCode = 'ISO_A3';

$input = json_decode(file_get_contents("../json/$path/centroides.geojson"),TRUE);

$output = array();

foreach($input["features"] as &$feature) {
  $output[$feature["properties"][$shapeCode]] = array(
    'Long' => $feature["geometry"]["coordinates"][0],
    'Lat' => $feature["geometry"]["coordinates"][1]
  );

}

file_put_contents("../json/$path/centroides-list.json", json_encode($output));
echo "wrote centroides-list.json";
