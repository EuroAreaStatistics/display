<?php

require_once __DIR__.'/API_get.php';

/**
 * Fetch project specific data from API server.
 *
 * Note: The list of all projects and their modification dates are fetched
 * via API_get before the project specific data is fetched.
 *
 * Returns JSON encoded error if the API does not respond correctly
 * and no cached response was found,
 * otherwise returns the (cached) response as a string.
 *
 * @param string $project
 * @param string $path
 * @return string
*/
function API_project_get($project, $path) {
  $projects = json_decode(API_get('/projects'), TRUE);
  $mtime = isset($projects['projects'][$project]['lastModified']) ? $projects['projects'][$project]['lastModified'] : NULL;
  return API_get($path, $mtime);
}
