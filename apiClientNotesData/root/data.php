<?php

require_once(__DIR__.'/../API_project_get.php');

$project = (isset($_REQUEST['project'])&& preg_match('/^[a-z0-9-]*$/',$_REQUEST['project'])) ? $_REQUEST['project'] : NULL;
$indicator = (isset($_REQUEST['id'])&& preg_match('/^[A-Za-z0-9_-]*$/',$_REQUEST['id'])) ? $_REQUEST['id'] : NULL;


function API_get_data($project, $indicator) {
  $path = '/data/'.$project.'/'.$indicator;
  $json = API_project_get($project, $path);
  return $json;
}

$content = API_get_data($project, $indicator);
header('Content-Type: text/plain');
echo ($content);

?>
