<?

//returns general labels and country names for a project/language

require_once(__DIR__.'/../API_project_get.php');

$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : NULL;
$project = (isset($_REQUEST['project'])&& preg_match('/^[a-z0-9-]*$/',$_REQUEST['project'])) ? $_REQUEST['project'] : NULL;
$page = 0;

$config = configureProject($project, $language);

if ($config == 'error') {
  require __DIR__.'/../../staticPages/404.php';
  exit;
}

$labels = array();


require(__DIR__.'/../../countryNames/getCountryNames.php');
$allCountries = getCountryList($config,$page);
$labels['lang_countries']           = getCountries ($config, $allCountries, $language, $themeURL, $page, 'standard');
$labels['lang_countries_titles']    = getCountries ($config, $allCountries, $language, $themeURL, $page, 'long');
$labels['lang_countries_list']      = getCountries ($config, $allCountries, $language, $themeURL, $page, 'standard', TRUE);

require(__DIR__.'/../../commonLabels/getCommonLabels.php');
$labels['lang_labels'] = getCommonLabels($language,$themeURL);
if (isset($config['tabs'][1]['lang'])) {
  $labels['lang_labels'] = array_merge($labels['lang_labels'],$config['tabs'][1]['lang']);
}
$labels['lang_labels']['langSelect'] = $shareLanguages;

$labels['countryRegions'] = ['MLT', 'CYP'];


header('Content-Type: application/json',true);
// header("Access-Control-Allow-Origin: *",true);
header('X-Content-Type-Options: nosniff',true);
echo json_encode($labels);

function configureProject($project, $language) {
  $path = '/project/' . $project . '/' . $language;
  $config = API_project_get($project, $path);
  $config = json_decode($config, TRUE);
  if (isset($config['error'])) {
    return 'error';
  }
  return $config;
}

function getCountries ($config, $allCountries, $language, $themeURL, $page, $mode='standard', $list=FALSE) {
// get standard countries and translations of non-country entities from $config

  if ($mode == 'long') {
    $lang_countries  = getCountryNames ($language,$themeURL,'long');
  } elseif ($mode == 'iso') {
    $lang_countries  = getCountryNames ($language,$themeURL,'ISO');
  } else {
    $lang_countries  = getCountryNames ($language,$themeURL);
  }

  $tabID = $config['project']['tabs'][$page];
  $tab = $config['tabs'][$tabID];

  $countries = array();

  foreach ($lang_countries as $k => $v) {
    if (!in_array($k,$allCountries)) continue;
    $countries[$k]=$v;
  }

  if (!empty($tab['labels'])) {

    $NoCountryEntities = array();

    if ($mode == 'iso') {
      foreach ($tab['labels'] as $k => $v) {
        $NoCountryEntities[strtolower($k)] = $k;
      }
    } else {
      foreach ($tab['labels'] as $k => $v) {
        $NoCountryEntities[strtolower($k)] = $v[$language];
      }
    }

    asort($NoCountryEntities);

    foreach ($NoCountryEntities as $k => $v) {
      if (!in_array($k,$allCountries)) continue;
      $countries[$k]=$v;
    }

  }

  if ($list) {
    return array_keys($countries);
  } else {
    return $countries;
  }

}


//returns a list of countries accross all displayed charts (fourLines buttons/barsLines dropdown/map counties with data)
function getCountryList ($config,$page) {
  $tabID = $config['project']['tabs'][$page];
  $tab = $config['tabs'][$tabID];
  $countries = array();
  foreach ($tab['charts'] as $i => $chartID) {
    $chart = $config['charts'][$chartID];

    if (isset($chart['data'])) {
      $key = array_search('LOCATION', $chart['data']['dimensions']);
      foreach ($chart['data']['keys'][$key] as $c) {
        $countries[$c] = true;
      }
    }
  }
  return array_map('strtolower', array_keys($countries));
}

