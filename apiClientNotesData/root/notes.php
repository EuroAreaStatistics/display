<?php

require_once(__DIR__.'/../API_project_get.php');

$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : NULL;
$country = (isset($_REQUEST['cr']) && preg_match('/^[A-Za-z0-9]*$/',$_REQUEST['cr'])) ? $_REQUEST['cr'] : NULL;
$project = (isset($_REQUEST['project'])&& preg_match('/^[a-z0-9-]*$/',$_REQUEST['project'])) ? $_REQUEST['project'] : NULL;


function API_get_note($project, $language, $country) {

  $path = '/note/'.$project.'/'.$language.'/'.$country;
  $json = API_project_get($project, $path);
  return $json;
}

$content = API_get_note($project, $language, $country);
header('Content-Type: text/plain');
echo ($content);

?>
