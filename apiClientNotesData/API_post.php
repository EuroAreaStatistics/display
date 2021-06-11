<?php

/**
 * Post data to API server.
 *
 * The following constants must be defined:
 *   API_PW: Pass code for API server (specific on snapshots and preview mode)
 *   API_URL: API server URL
 *   API_CACHE_DIR: local cache directory (for error count)
 *   API_CACHE_ADMIN: email addresses to notify in case of API errors
 *   API_CACHE_MAX_ERRORS: maximum number of errors after which email notifications are sent
 *
 * Returns JSON encoded error if the API does not respond correctly,
 * otherwise returns the (cached) response as a string.
 *
 * @param string $path
 * @param mixed $args
 * @return string
*/
function API_post($path, $args) {
  $track = ini_set('track_errors', '1');
  $errors = array();
  $counter = API_CACHE_DIR . '/errors.txt';
  $result = FALSE;
  if ($result === FALSE) {
    $url = API_URL.'/'.API_PW.$path;
    $ch = curl_init($url);
    if ($ch === FALSE) {
      $errors[] = $php_errormsg;
    } else {
      $options = array(
        CURLOPT_CAINFO => __DIR__.'/../vendors/curl-ca-bundle/src/ca-bundle.crt',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FAILONERROR => 1,
        CURLOPT_ENCODING => '', // any compression type supported by curl
        CURLOPT_HTTPHEADER => array('Accept: application/json'),
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => json_encode($args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_USERAGENT => 'cyc-display/'.__FUNCTION__,
      );
      if (!curl_setopt_array($ch, $options)) {
        $errors[] = $php_errormsg;
      } else {
        $result = curl_exec($ch);
        if ($result !== FALSE) {
          if (defined('API_CACHE_ADMIN')) {
            // reset error counter
            @unlink($counter);
          }
        } else {
          if (curl_errno($ch) != CURLE_HTTP_RETURNED_ERROR) {
            $error = sprintf("%s: curl error %d (%s) [%s]", __FUNCTION__, curl_errno($ch), curl_error($ch), $url);
            $errors[] = $error;
            error_log($error);
          } else {
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = sprintf("%s: HTTP status %d [%s]", __FUNCTION__, $status, $url);
            $errors[] = $error;
            error_log($error);
          }
        }
      }
      curl_close($ch);
    }
  }
  ini_set('track_errors', $track);
  if ($result === FALSE) {
    // API error
    error_log(sprintf("%s: API failed for %s", __FUNCTION__, $path));
    if (defined('API_CACHE_ADMIN')) {
      // increment error counter
      $c = (int)@file_get_contents($counter);
      $c++;
      if ($c <= API_CACHE_MAX_ERRORS) {
        file_put_contents($counter, (string)$c);
      }
      if ($c === API_CACHE_MAX_ERRORS) {
        mail(API_CACHE_ADMIN, "API failed for $path", implode("\r\n", $errors));
      }
    }
  }
  if ($result === FALSE) {
    $result = json_encode(array('error' => 'not found'));
  }
  return $result;
}
