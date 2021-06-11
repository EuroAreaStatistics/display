<?php

// convert ISO2 code to ISO3 code and add to features in map files

require '../02/dataFetcher/ISOmap.php';

ini_set ('display_errors','ON');

if (php_sapi_name() != 'cli') header('Content-type: text/plain');

$lines = preg_split("/=/", file_get_contents("../02resources/maps/ecb/mapShapes.js"), 2);

$json = json_decode($lines[1],TRUE);

$isomap = array_merge($ISO2_ISO3, $ISO2_ISO3_alt);

foreach($json["features"] as &$feature) {
  $iso2 = $feature["properties"]["CNTR_ID"];
  $country = $feature["properties"]["NAME_ENGL"];
  if (isset($isomap[$iso2])) {
    $feature["properties"]["ISO_A3"] = $isomap[$iso2];
  } else {
    $feature["properties"]["ISO_A3"] = 'XXX';
    echo "no code for ISO2 code '$iso2' - '$country'\n";
  }
}

file_put_contents("mapShapes.js", $lines[0] . '=' . json_encode($json));
echo "wrote mapShapes.js\n";
