<?php

$type = (isset($_REQUEST['type']) && preg_match('/^[a-zA-Z]*$/',$_REQUEST['type'])) ? $_REQUEST['type'] : 'shapes';
$variant = (isset($_REQUEST['variant']) && preg_match('/^[a-zA-Z0-9]*$/',$_REQUEST['variant'])) ? $_REQUEST['variant'] : NULL;
$themeURL = (isset($_REQUEST['th']) && preg_match('/^[a-z]*$/',$_REQUEST['th'])) ? $_REQUEST['th'] : 'oecd';
$country = (isset($_REQUEST['cr']) && preg_match('/^[a-zA-Z]*$/',$_REQUEST['cr'])) ? $_REQUEST['cr'] : null;
$variable = (isset($_REQUEST['vr']) && preg_match('/^[a-zA-Z_]*$/',$_REQUEST['vr'])) ? $_REQUEST['vr'] : 'mapElement';
$mode = (isset($_REQUEST['mode']) && preg_match('/^[a-zA-Z]*$/',$_REQUEST['mode'])) ? $_REQUEST['mode'] : 'js';

include __DIR__.'/../getMaps.php';

$map = getMaps ($country,$type,$themeURL,$variant);
$mtime = $map !== null ? filemtime($map) : null;

if ($mtime !== null &&
    isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) &&
    $mtime <= strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
  header('HTTP/1.1 304 Not Modifed');
  exit;
}

if ($mode == 'json') {

  header('Content-Type: application/json');
//  header("Access-Control-Allow-Origin: *");
  header('X-Content-Type-Options: nosniff');

  if ($mtime !== null) {
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
  }
  if ($map === null) {
    echo 'null';
  } else {
    readfile($map);
  }

} else {

  header('Content-Type: application/javascript');
//  header("Access-Control-Allow-Origin: *");
  header('X-Content-Type-Options: nosniff');
  if ($mtime !== null) {
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
  }
  echo '/**/var ', $variable, ' =';
  if ($map === null) {
    echo 'null';
  } else {
    readfile($map);
  }

}
