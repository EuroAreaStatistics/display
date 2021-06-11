<?php
ini_set ('display_errors','OFF');
//error_reporting(-1);

// read $urls[] from config file
require_once dirname(__DIR__) . '/BaseURL.php';
require_once dirname(__DIR__) . '/apiClientNotesData/API_get.php';

if (defined('CORS_HEADER')) {
  header("Access-Control-Allow-Origin: *", true);
}

if (isset($_SERVER['HTTP_ORIGIN'])) {
  header('Access-Control-Allow-Credentials: true', true);
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization', true);
  header('Access-Control-Allow-Methods: GET, OPTIONS', true);
  exit;
}

if (!defined('ACCESS')) {
    define('ACCESS', 'public');    
}

// return 404 error page
function error404() {
    $errorScript = '/staticPages/404.php';

    $file = dirname(__DIR__) . $errorScript;
    if (!file_exists($file)) {
        header("HTTP/1.1 404 Not Found"); 
        echo "Not found";
        exit;
    }
    return $file;
}

// use a function to avoid setting global variables
function __urlMapper($urls) {
    global $projectsWizard;
    $projectsWizard = array();

    if (isset($_SERVER['PATH_INFO'])) $prefix = $_SERVER['PATH_INFO'];
    else $prefix = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');

    // load project list via API
    $projects = json_decode(API_get('/projects'), TRUE);
    if (isset($projects['projects'])) {
      $projectsWizard = $projects['projects'];
    }
    if (isset($projects['urls'])) {
      $urls = array_replace($urls, $projects['urls']);
    }

    // lookup current URL in $urls[]
    // and redirect browser to new url

    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// remove prefix
    if (substr($url, 0, strlen($prefix)) == $prefix) $url = substr($url, strlen($prefix));
// remove trailing slash
    if ($url != '/') $url = rtrim($url, '/');
    $urlProject = ltrim($url, '/');

    if (ACCESS=='public') {
      foreach ($projectsWizard as $k => $v) {
        if (isset($v['underConstruction'])) {
          $urls['/'.$k]     = '/staticPages/under-construction?wizard=on&wizardProject='.$k;
// redirects for wizard apps
        } else {
          $urls['/'.$k]     = '/03/wizard?wizardProject='.$k;
        }
      }
    }



//serve wizard files through API
    if (ACCESS=='preview') {

      if (preg_match('#^/([a-z0-9-]+)/?$#', $url, $matches) && (substr($url,0,4) == '/sl-')) {
        if (!array_key_exists($matches[0],$urls)) {
          $urls['/'.$matches[1]]     = '/03/wizardShort?wizardProject='.$matches[1];
        }

      } else if (preg_match('#^/([a-z0-9-]+)/?$#', $url, $matches)) {
        if (!array_key_exists($matches[0],$urls)) {
          $urls['/'.$matches[1]]     = '/03/wizard?wizardProject='.$matches[1];
        }
      }
    } else if (ACCESS=='snapshots') {
      if (preg_match('#^/([a-z0-9-]+)/?$#', $url, $matches) && (substr($url,0,3) == '/s-') ) {
        if (!array_key_exists($matches[0],$urls)) {
          $urls['/'.$matches[1]]     = '/03/wizard?wizardProject='.$matches[1];
        }
      } else if (preg_match('#^/([a-z0-9-]+)/?$#', $url, $matches) && (substr($url,0,4) == '/sl-') ) {
          $urls['/'.$matches[1]]     = '/03/wizardShort?wizardProject='.$matches[1];
      }
    }


    $matched = 0;
    $redirect = FALSE;

    foreach($urls as $src => $dest) {
        if (is_array($dest)) {
            $destination = $dest['redirect'];
        } else {
            $destination = $dest;
        }
        $new_url = preg_replace('#^'.trim($src).'$#i', trim($destination), $url, -1, $matched);
        if ($matched > 0) {
          if (is_array($dest)) {
            $redirect = TRUE;
          }
          break;
        }
    }


    if ($redirect) {
        if (count($_GET)) {
            if (strpos($new_url, '?') === FALSE) $new_url .= '?';
            else $new_url .= '&';
            $new_url .= http_build_query($_GET);
        }
        if (preg_match('@^/([^/]|$)@', $new_url)) {
            $new_url = $GLOBALS['baseURL'] . $new_url;
        }
        Header("Location: $new_url");
        exit;
    }


    $parts = parse_url($new_url);

    // set query parameters
    if (isset($parts['query'])) {
        parse_str($parts['query'], $vars);
        $_REQUEST = array_merge($_REQUEST, $vars);
        $_GET = array_merge($_GET, $vars);
     }


    $file = $parts['path'];


    // find absolute path of PHP file
    $suffix = '.php';
    $file = rtrim($file, '/');
    if (substr($file, - strlen($suffix), strlen($suffix)) != $suffix) $file .= $suffix;
    $file = dirname(__DIR__) . $file;
    if (!file_exists($file)) return error404();

    return $file;
}

require_once __urlMapper($urls);
