# Compare your country data viz framework

Set-up on localhost

1. Add mainConfig.php into this directory

========================

//API settings

//API password, use 'test' to access live projects only
define('API_PW', 'test');

//API server URL
define('API_URL', 'http://example.com/api/v1');

//API client cache directory (create the directory manually and add write permission for your local server)
define('API_CACHE_DIR', __DIR__.'/cache');

//API client time to load from cache
define('API_CACHE_TTL', 60 * 60); // 1 hour

//API client timeout for load from backend
define('API_CACHE_TIMEOUT', 2); // 2 seconds



//Add global variables for project path and theme
$baseURL = '//cyc.local';                           //replace 'cyc.local' by your local virtual host
$liveURL = $baseURL;
$themeURL = 'default';                              //define your theme (choose from 'default', 'oecd', 'ecb')

//New features to enable or disable
$features = array(
  'embedSingle' => FALSE, // embed form to embed a single indicator [ALPHA]
  'buttonFlags' => FALSE, // add country flags to country buttons in trends template
);

========================


2. Add the following .htaccess file into this directory

=========================

#add .htpasswd path for stage and preview version

RewriteEngine On

RewriteRule ^resources/ - [L]
RewriteRule ^02resources/ - [L]
RewriteRule ^03resources/ - [L]
RewriteRule ^ /root/index.php [L]

=========================


