<?php

$source[0] = json_decode(file_get_contents('../json/langCountries_en.json'),TRUE);
$source[1] = json_decode(file_get_contents('../json/langCountriesLong_en.json'),TRUE);
$source[2] = json_decode(file_get_contents('../json/langCountryGroups_en.json'),TRUE);


$out = fopen('countries_cn.csv', 'w');
fputs($out, "key,value\n");
foreach ($source as $type) {
  foreach ($type as $k => $v) {
    $t = $k.',"'.$v.'"';
    fputs($out, "$t\n");
  }
}
fclose($out);
