<?php

// creates centroides list from centroides shapes

ini_set ('display_errors', 'ON');
ini_set ('serialize_precision', '14');
header('Content-type', 'text/plain');

foreach ((array)glob(__DIR__.'/../json/*/*centroides.geojson') as $file) {
  $input = json_decode(file_get_contents($file), TRUE);
  $config = dirname($file) . '/featureReferenceCode.json';
  $shapeCode = json_decode(file_get_contents($config), TRUE);

  $output = [];

  foreach($input["features"] as &$feature) {
    $output[$feature["properties"][$shapeCode]] = [
      'Long' => $feature["geometry"]["coordinates"][0],
      'Lat' => $feature["geometry"]["coordinates"][1],
    ];
  }
  // add EU28 as a copy of EUN
  if (array_key_exists("EUN", $output) && !array_key_exists("EU28", $output)) {
    $output["EU28"] = $output["EUN"];
  }
  // add regions with dummy coordinate
  foreach (['4A', '9A', 'R12'] as $code) {
    if (!array_key_exists($code, $output)) {
      $output[$code] = [
        'Long' => 0,
        'Lat' => 0,
      ];
    }
  }

  $outfile = realpath(dirname($file) . '/' . basename($file, '.geojson') . '-list.json');
  file_put_contents($outfile, json_encode($output));
  echo "wrote $outfile\n";
}

echo "DONE\n";
