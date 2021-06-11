<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('HTTP/1.1 405 Method Not Allowed');
  exit;
}

require_once(__DIR__.'/../API_post.php');

function API_embed($settings) {
  $path = '/embed/' . $settings['project'];
  unset($settings['project']);
  return API_post($path, $settings);
}

$content = API_embed(json_decode(file_get_contents('php://input'), TRUE));
header('Content-Type: application/json');
if (file_exists(API_CACHE_DIR . '/projects.json')) {
  touch(API_CACHE_DIR . '/projects.json', time() - API_CACHE_TTL);
}
echo $content;
