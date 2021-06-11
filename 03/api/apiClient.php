<?php

require_once(__DIR__.'/../../apiClientNotesData/API_project_get.php');

//add general template list and theme specific templates
require_once(mainFile('templateList.php','config'));
if (file_exists(mainFile('themes/'.$themeURL.'/templateListTheme.php','config'))) {
  require_once(mainFile('themes/'.$themeURL.'/templateListTheme.php','config'));
  $templateList = array_replace_recursive($templateList, $templateListTheme);
}

//URL parameters
$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : 'en';
$countries = (isset($_REQUEST['cr']) && preg_match('/^([A-Za-z0-9]| )*$/',$_REQUEST['cr'])) ? explode(' ', $_REQUEST['cr']) : array('oecd');
$page = (isset($_REQUEST['page'])&& preg_match('/^[0-9]*$/',$_REQUEST['page'])) ? $_REQUEST['page'] : 0;
$secondVisit = (isset($_REQUEST['visited'])&& preg_match('/^[0-9]{1}$/',$_REQUEST['visited'])) ? $_REQUEST['visited'] : 0;
$template = (isset($_REQUEST['template'])&& preg_match('/^[A-Za-z0-9]*$/',$_REQUEST['template'])) ? $_REQUEST['template'] : NULL;
$Chart = (isset($_REQUEST['charts']) && preg_match('/^([a-z0-9._-]| )*$/i',$_REQUEST['charts'])) ? $_REQUEST['charts'] : NULL;
$pdf = (isset($_REQUEST['pdf']) && preg_match('/^([0-9]| )*$/i',$_REQUEST['pdf'])) ? $_REQUEST['pdf'] : 0;
$embed = (isset($_REQUEST['embed']) && preg_match('/^[1-9]$/',$_REQUEST['embed'])) ? $_REQUEST['embed'] : FALSE;


//URL parameters in javascript
// 'yr' for 'YEAR' dimension in CYC data format, separated by '+', used in simpleCharts.js
// 'lc' for 'LOCATION' dimension in CYC data format, separated by '+', used in simpleCharts.js
// 'color' to define color of simpleCharts.js header
// 'chart' to define selectied indicator in simpleCharts.js
// 'template' to define template in simpleCharts.js
// 'title' - simpleCharts.js - not in use
// 'subtitle' - simpleCharts.js - not in use

//dependent parameters
$project = (isset($_REQUEST['wizardProject'])&& preg_match('/^[a-z0-9-]*$/',$_REQUEST['wizardProject'])) ? $_REQUEST['wizardProject'] : NULL;
$country = $countries[0];



// get WizardTemplatesModules, lang_countries and lang
require(__DIR__.'/../../commonLabels/getCommonLabels.php');
$lang = getCommonLabels($language,$themeURL);

require(__DIR__.'/../libsPHP/themeSettings/'.$themeURL.'.php');

//set default tempate for simple chart and regular chart
if (substr($project,0,2) == 's-' && $template === NULL) {
  $template = 'sbar';
}


$config = configureProject($project, $language);

if ($config == 'error') {
  require __DIR__.'/../../staticPages/404.php';
  exit;
}

$countriesOffshore = ["AND", "ATG", "AIA", "ABW", "BRB", "BHR", "BMU", "BHS", "BLZ", "COK", "CUW", "DMA", "GRD", "GGY", "GIB", "IMN", "JEY", "KNA", "CYM", "LBN", "LCA", "LIE", "LBR", "MHL", "MSR", "MUS", "NRU", "NIU", "PAN", "PHL", "SYC", "SGP", "SXM", "TCA", "VCT", "VGB", "VIR", "VUT", "WSM"];

// get countries present in all charts (used for fourLines buttons, barsLines dropdown, mapGroup)
$allCountries = getCountryList($config,$page);

require(__DIR__.'/../../countryNames/getCountryNames.php');
if (isset($lang['country'])) {
  $countriesOrder = ['R12'=>1, '4A'=>2, '9A'=>3, 'EUR'=>10];
  $lang_countries = array_intersect_key($lang['country'], array_flip($allCountries));
  unset($lang['country']);
  array_walk($lang_countries, function (&$v, $k) use ($countriesOrder) {
    $k = strtoupper($k);
    $v = [isset($countriesOrder[$k]) ? $countriesOrder[$k] : 0, $v];
  });
  $coll = collator_create($language);
  uasort($lang_countries, function ($a, $b) use ($coll) {
    if ($a[0] === $b[0]) {
      return collator_compare($coll, $a[1], $b[1]);
    }
    return ($a[0] < $b[0]) ? -1 : 1;
  });
  array_walk($lang_countries, function (&$v, $k) use ($countriesOrder) {
    $v = $v[1];
  });
} else {
  $lang_countries = getCountries ($config, $allCountries, $language, $themeURL, $page, 'standard');
}
if (isset($lang['countryLong'])) {
  $lang_countries_titles = array_merge($lang_countries, array_intersect_key($lang['countryLong'], array_flip($allCountries)));
  unset($lang['countryLong']);
} else {
  $lang_countries_titles = getCountries ($config, $allCountries, $language, $themeURL, $page, 'long');
}
$lang_countries_ISO     = getCountries ($config, $allCountries, $language, $themeURL, $page, 'iso');

// add additioanl global variables
$chartType = null;
$template = getTemplate ($config,$template,$page);
configureWizardProject($config,$template);


function getCountries ($config, $allCountries, $language, $themeURL, $page, $mode='standard') {
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

  return $countries;

}


function configureProject($project, $language) {
  $path = '/project/' . $project . '/' . $language;
  $config = API_project_get($project, $path);
  $config = json_decode($config, TRUE);
  if (isset($config['error'])) {
    return 'error';
  }
  return $config;
}


function getTemplate ($config,$template,$page) {
  $tabID = $config['project']['tabs'][$page];
  $tab = $config['tabs'][$tabID];

  if ($template != null) {
     return $template;
  } else {
     return $tab['template'];
  }
}


function configureWizardProject($config,$template) {
  global $language, $page, $templateList;


  $tabID = $config['project']['tabs'][$page];
  $tab = $config['tabs'][$tabID];

  //if ($GLOBALS['template'] != null) {
  //   $tab['template'] = $GLOBALS['template'];
  //} else {
  //   $GLOBALS['template'] = $tab['template'];
  //}

  // set max number of charts for selected templates
  if (!isset($templateList[$template]['options']['maxCharts'])) {
    $charts = count($tab['charts']);
  } else {
    $charts = min($templateList[$template]['options']['maxCharts'], count($tab['charts']));
  }

  // set selected charts from URL parameters
  if (is_string($GLOBALS['Chart'])) {
    // new format: comma separated
    $GLOBALS['Chart'] = explode(' ', $GLOBALS['Chart']);
    $GLOBALS['Chart'] = array_slice($GLOBALS['Chart'], 0, $charts);
    $charts = count($GLOBALS['Chart']);
  } else {
    $GLOBALS['Chart'] = array();
  }
  // set any invalid charts to the original charts
  for ($i = 0; $i < $charts; $i++) {
    if (!isset($GLOBALS['Chart'][$i]) || !in_array($GLOBALS['Chart'][$i], $tab['charts'])) {
      $GLOBALS['Chart'][$i] = $tab['charts'][$i];
    }
  }

  foreach ($tab['charts'] as $chartID) {
     $chart = $config['charts'][$chartID];
     $name = $chartID;
     if (!isset($chart['data'])) {
       // empty data set
       $chart['data'] = array(
         'dimensions' => array('LOCATION', 'YEAR'),
         'keys' => array(array(), array()),
         'data' => array(),
       );
     }

     $GLOBALS['ConfigProject'][$name] = array(
        'title'       => $chart['title'][$language],
        'definition'  => $chart['definition'][$language] ?? '',
        'options'     => $chart['options'],
        'data'        => $chart['data'],
        'download'    => $GLOBALS['baseURL'].'/data?project='.$GLOBALS['project'].'&chart='.$name,
     );

  }


//Set template specific global options ($chartType, $ChartDisplay...)
  $GLOBALS['altHeader'] = FALSE;
  foreach ($templateList[$template]['options'] as $option => $value) {
    $GLOBALS[$option] = $value;
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


