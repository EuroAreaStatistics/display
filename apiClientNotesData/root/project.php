<?php

// returns project json data for a language

require_once(__DIR__.'/../API_project_get.php');

$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : NULL;
$project = (isset($_REQUEST['project'])&& preg_match('/^[a-z0-9-]*$/',$_REQUEST['project'])) ? $_REQUEST['project'] : NULL;


function API_get_project($project, $language) {
  $path = '/project/' . $project . '/' . $language;
  return API_project_get($project, $path);
}

$content = API_get_project($project, $language);
header('Content-Type: application/json');
// header("Access-Control-Allow-Origin: *", true);
header('X-Content-Type-Options: nosniff', true);
echo $content;
