<?php

ini_set ('display_errors','ON');

$country = (isset($_REQUEST['cr']) && preg_match('/^[a-zA-Z]*$/',$_REQUEST['cr'])) ? $_REQUEST['cr'] : null;

include __DIR__.'/../getFlags.php';

$flag = getFlags ($country);

//$flag = __DIR__.'/../flags/OECD.png';

$mtime = filemtime($flag);

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) &&
    $mtime <= strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
  header('HTTP/1.1 304 Not Modifed');
  exit;
}

header('Content-Type: image/png');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
readfile($flag);
