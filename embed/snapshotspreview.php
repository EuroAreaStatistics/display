<?php

//ini_set ('display_errors','ON');
require_once(__DIR__.'/../countryNames/getCountryNames.php');
require_once(__DIR__.'/../apiClientNotesData/API_project_get.php');


$lang_countries = getCountryNames ('en',$themeURL);

$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : 'en';
$project = (isset($_REQUEST['project'])&& preg_match('/^[A-Za-z0-9-]*$/',$_REQUEST['project'])) ? $_REQUEST['project'] : 'error';
$template = (isset($_REQUEST['template'])&& preg_match('/^[A-Za-z0-9]*$/',$_REQUEST['template'])) ? $_REQUEST['template'] : 'sbar';
$color = (isset($_REQUEST['template'])&& preg_match('/^[A-Za-z0-9]*$/',$_REQUEST['color'])) ? $_REQUEST['color'] : 'purple';
$chart = (isset($_REQUEST['chart'])&& preg_match('/^[A-Za-z0-9-]*$/',$_REQUEST['chart'])) ? $_REQUEST['chart'] : null;



require('../mainConfig.php');
require('../config/templateList.php');
require('../config/wizardMode.php');

$shareLanguages=array(
        'bg' => 'Български',
        'cs' => 'Čeština',
        'da' => 'Dansk',
        'de' => 'Deutsch',
        'et' => 'Eesti keel',
        'en' => 'English',
        'es' => 'Español',
        'el' => 'Eλληνικά',
        'fr' => 'Français',
        'hr' => 'Hrvatski',
        'it' => 'Italiano',
        'jp'    => '日本語',
        'ko'    => '한국어',
        'lv' => 'Latviešu',
        'lt' => 'Lietuvių',
        'hu' => 'Magyar',
        'mt' => 'Malti',
        'nl' => 'Nederlands',
        'ru'    => 'по-ру́сски',
        'pl' => 'Polski',
        'pt' => 'Português',
        'ro' => 'Română',
        'sk' => 'Slovenčina',
        'sl' => 'Slovenščina',
        'fi' => 'Suomi',
        'sv' => 'Svenska',
);

if (substr($project,0,2) == 's-') {
  $chartOptions = $wizardMode['simple'];
  if (!defined('SNAPSHOT_EMBED_URL')) {
    $embedURL = $baseURL;
  } else {
    $embedURL = SNAPSHOT_EMBED_URL;
  }
} else {
  $chartOptions = $wizardMode['simpleTransfer'];
  if (!defined('EMBED_URL')) {
    $embedURL = $baseURL;
  } else {
    $embedURL = EMBED_URL;
  }
}

$config = configureProject($project, $language);

$Charts = array();
foreach ($config['charts'] as $k => $v) {
  array_push($Charts,$k);
}

if ($config['languages']) {
  $embedLanguages = $config['languages'];
} else {
  $embedLanguages = [];
}
array_push($embedLanguages,'en');

$possilble_crs = $config['charts'][$Charts[0]]['data']['keys'][0];
$possilble_years = $config['charts'][$Charts[0]]['data']['keys'][1];

function configureProject($project, $language) {
  $path = '/project/' . $project . '/' . $language;
  $config = API_project_get($project, $path);
  $config = json_decode($config, TRUE);
  if (isset($config['error'])) {
    return 'error';
  }
  return $config;
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>CYC snapshots preview</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/png" href="<?= $staticURL ?>/img/<?= $themeURL ?>/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= $vendorsURL ?>/normalize-css/normalize.css" />
    <style>
<?php include __DIR__.'/snapshotspreview.css'; ?>
    </style>
    <script src='<?= $vendorsURL ?>/jquery/jquery.min.js'></script>
    <script src='<?= $vendorsURL ?>/jquery-ui/jquery-ui.min.js'></script>
    <script src="<?= $vendorsURL ?>/modernizr/modernizr.js"></script>
    <script>
<?php include __DIR__.'/snapshotspreview.js'; ?>
    </script>
    <script>
      var project = <?= json_encode($project) ?>;
      var lang = <?= json_encode($language) ?>;
      var template = <?= json_encode($template) ?>;
      var color = <?= json_encode($color) ?>;
      var chart = <?= json_encode($chart) ?>;
      var baseURL = <?= json_encode($baseURL) ?>;
      var embedURL = <?= json_encode($embedURL) ?>;
      var Charts = <?= json_encode($Charts) ?>;
      var wizardConfig = <?= json_encode($config) ?>;
    </script>
  </head>
  <body>
    <div class='embedSwitch'>
      <a href='/embed?project=<?= $project ?>'>Embed entire site</a>
      <span '>Embed individual chart (beta)</span>
    </div>
    <div class="resourceContent">
      <div id="storyPanel" class="configPanel">
        <table id="sharetable">
          <tr>
            <td>
              <div class='feldtitel'>Select indicator:</div>
              <select id="select_chart" name="select_chart">
<?php foreach($config['charts'] as $chartKey => $chart) : ?>
                <option value="<?= $chartKey ?>"><?= $chart['title'][$language] ?></option>
<?php endforeach ?>
              </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>
              <div class='feldtitel'>Language:</div>
              <select id="select_lang">
<?php foreach($embedLanguages as $v): ?>
                <option value="<?= $v ?>"><?= $shareLanguages[$v] ?></option>
<?php endforeach; ?>
              </select>
            </td>
            <td>
              <div class='feldtitel'>Template:</div>
              <select id="select_template">
<?php foreach ($chartOptions as $k => $v) : ?>
                <option value="<?= $v ?>"><?= $templateList[$v]['name'] ?></option>
<?php endforeach ?>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <div class='feldtitel'>Embed size:</div>
              <select id="select_size">
                <option value=""></option>
                <option value="450x585">450 x 585 pixel</option>
                <option value="custom">custom size</option>
              </select>
            </td>
            <td>
              <div class='feldtitel'>Header color:</div>
              <select id="select_color">
                <option value="purple">OECD purple</option>
                <option value="blue">OECD blue</option>
                <option value="green">OECD green</option>
                <option value="red">OECD red</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <div id='custom-size-fields' style="display:none; width:70%">
                Custom Size:
                <input type="text" name="customX" id="customX" value="500"  size="3" /> x
                <input type="text" name="customY" id="customY" value="570" size="3" /> Pixel
                <button id="custom-size-go">Set</button>
              </div>
            </td>
            <td>
            </td>
          </tr>
        </table>

<!--        <div class='feldtitel'>Add chart title:</div>
        <div id='titleInput'>
          <input class="titleInput" type="text" name="customTitle" id="customTitle" value="<?= $config['charts'][$Charts[0]]['title'][$language] ?>">
          <button id="custom-title-go">Set</button>
        </div>
        <div class='feldtitel'>Add chart subtitle:</div>
        <div id='subTitleInput'>
          <input class="titleInput" type="text" name="customSubTitle" id="customSubTitle" value="<?= $config['charts'][$Charts[0]]['definition'][$language] ?>">
          <button id="custom-subtitle-go">Set</button>
        </div>
-->
        <div class='seriesSelect'>
          <div class='feldtitel'>Set countries (overwrite default):</div>
          <div id='countryInput'>
  <?php foreach($possilble_crs as $val):?>
            <input class="countrySelect" type="checkbox" name="country" value="<?= $val ?>"><?= $val ?>
  <?php endforeach; ?>
          </div>
          <div class='feldtitel'>Set years:</div>
          <div id='yearInput'>
  <?php foreach($possilble_years as $val):?>
            <input class="yearSelect" type="checkbox" name="year" value="<?= $val ?>"><?= $val ?>
  <?php endforeach; ?>
          </div>
        </div>


        <div class='feldtitel1'>Embed code:</div>
        <textarea id="sharecode" class="urlTextarea"></textarea>
      </div>
    </div>
    <div class="background">
      <div class="resourceContent">
        <div id="canvas-wrapper"></div>
      </div>
    </div>
  </body>
</html>
