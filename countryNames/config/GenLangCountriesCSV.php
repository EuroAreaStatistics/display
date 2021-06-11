<?php

$lgs = array('en','de','es', 'fr', 'it', 'jp', 'ko', 'pl', 'pt', 'ru', 'cn');
foreach ($lgs as $lg) {
  include("langmain_$lg.php");
  foreach($lang_countries as $k => $v) $a[$k][$lg]=$v;
}
$out = fopen('lang_master_countries.csv', 'w');
fputs($out, "key," . join(",", $lgs) . "\n");
foreach ($a as $k => $d) {
  $t = "$k";
  foreach ($lgs as $lg) {
    $v = str_replace('"','""',$d[$lg]);
    $t .= ',"'.$v.'"';
  }
  fputs($out, "$t\n");
}
fclose($out);
