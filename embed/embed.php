<?php

//ini_set ('display_errors','ON');
require_once(__DIR__.'/../countryNames/getCountryNames.php');
require_once(__DIR__.'/../apiClientNotesData/API_project_get.php');
require_once(__DIR__.'/../03/libsPHP/themeSettings/'.$themeURL.'.php');

$lang_countries = getCountryNames ('en',$themeURL);

$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : 'en';
$country = (isset($_REQUEST['cr']) && preg_match('/^[A-Za-z0-9]*$/',$_REQUEST['cr'])) ? $_REQUEST['cr'] : 'oecd';
$project = (isset($_REQUEST['project'])&& preg_match('/^[A-Za-z0-9-]*$/',$_REQUEST['project'])) ? $_REQUEST['project'] : 'error';
$page = (isset($_REQUEST['page'])&& preg_match('/^[0-9]*$/',$_REQUEST['page'])) ? $_REQUEST['page'] : 0;

//sets country list for country buttons - default setting on OECD countries

if (!defined('EMBED_URL')) {
  $embedURL = $baseURL;
} else {
  $embedURL = EMBED_URL;
}

if ($themeURL == 'oecd') {
  $group = array(
                        'aus', 'aut', 'bel', 'can', 'che', 'chl', 'cze', 'deu',
                        'dnk', 'esp', 'est', 'fin', 'fra', 'gbr', 'grc', 'hun',
                        'irl', 'isl', 'isr', 'ita', 'jpn', 'kor', 'lux', 'mex',
                        'nld', 'nor', 'nzl', 'oecd', 'pol', 'prt', 'svk', 'svn',
                        'swe', 'tur', 'usa'
                    );
  $title = 'Compare your country by OECD';
} else if ($themeURL == 'ecb') {
  $group = array(
                        'aut', 'bel', 'cyp', 'deu', 'esp', 'fin', 'fra', 'grc',
                        'irl', 'ita', 'ltu', 'lux', 'lva', 'mlt', 'nld', 'prt',
                        'est', 'svk', 'svn', 'eur',
                    );
  $title = 'Euro area statistics';
}

if ($project === '') {
  $config = array();
} else {
  $config = configureProject($project, $language);
}

if (isset($config['languages'])) {
  $embedLanguages = $config['languages'];
} else {
  if ($themeURL == 'ecb') {
    $embedLanguages = array_keys($shareLanguages);
  } else {
    $embedLanguages = [];
  }
}
$embedLanguages = array_merge($embedLanguages, array('en'));

$tempateWithCountries = templateWithCountries($config, $page);
$numberOfCharts = count((array)$config['tabs'][$config['project']['tabs'][$page]]['charts']);

function configureProject($project, $language) {
  $path = '/project/' . $project . '/' . $language;
  $config = API_project_get($project, $path);
  $config = json_decode($config, TRUE);
  if (isset($config['error'])) {
    return 'error';
  }
  return $config;
}


function templateWithCountries($config, $page){
  $templatesWithCountries = [1];
  if (isset($config['project']['tabs'])) {
    $tab=$config['project']['tabs'][$page];
    $template=$config['tabs'][$tab]['template'];
    if (in_array($template,$templatesWithCountries)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}


?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $title ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" type="image/png" href="<?= $staticURL ?>/img/<?= $themeURL ?>/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= $vendorsURL ?>/chosen/chosen.css">
    <style>
<?php include __DIR__.'/embed.css'; ?>
    </style>

    <script src='<?= $vendorsURL ?>/jquery/jquery.min.js'></script>
    <script src='<?= $vendorsURL ?>/chosen/chosen.jquery.js'></script>
    <script src='<?= $vendorsURL ?>/jquery-ui/jquery-ui.min.js'></script>
    <script src="<?= $vendorsURL ?>/modernizr/modernizr.js"></script>

    <script>
<?php include __DIR__.'/embed.js'; ?>
    </script>
    <script>
      var default_country = <?= json_encode($country) ?>;
      var project = <?= json_encode($project) ?>;
      var lang = <?= json_encode($language) ?>;
      var page = <?= json_encode($page) ?>;
      var baseURL = <?= json_encode($baseURL) ?>;
      var embedURL = <?= json_encode($embedURL) ?>;
      var width = 1100;
      var height = 700;
      var width1 = 1100;
      var height1 = 700;
    </script>

<?php require(__DIR__.'/../03/analytics/'.$themeURL.'.php'); ?>

  </head>
  <body>
    <div id="embedContainer" class="resourceContainer">
      <div id="embedContent" class="resourceContent">
        <div id="storyPanel" class="bottomPanel">
<?php if (!empty($GLOBALS['features']['embedSingle'])): ?>
<?php   if ($numberOfCharts > 1): ?>
          <div class="embedSwitch">
            Please select <a href="<?= htmlspecialchars('/embed-single?'.http_build_query(array('project'=>$project,'cr'=>$country,'lg'=>$language,'page'=>$page))) ?>">embed individual chart</a> or continue below for embedding the full webpage.
          </div>
<?php   endif ?>
<?php endif // embedSingle ?>
          <div>
            <span>Select your preferences and paste the embed code into your website or blog.</span>
          </div>
          <table id="sharetable">
            <tr>
              <td>
                <div class='feldtitel'>Select language:</div>
                  <select id="select_lang">
<?php foreach($shareLanguages as $k => $v):
  if (!in_array($k,$embedLanguages)) continue;
?>
                     <option value="<?= $k ?>"><?= $v ?></option>
<?php endforeach; ?>
                  </select>
              </td>
              <td>
                <div class='feldtitel'>Select embed size:</div>
                <select id="select_size">
                    <option value=""></option>
                    <option value="700x645">700 x 645 pixel</option>
                    <option value="450x585">450 x 585 pixel</option>
                    <option value="custom">custom size</option>
                </select>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <div id='custom-size-fields' style="display:none;">
                  Custom Size:
                  <input type="text" name="customX" id="customX" value="500"  size="3" /> x
                  <input type="text" name="customY" id="customY" value="570" size="3" /> Pixel
                  <button id="custom-size-go">Set</button>
                </div>
              </td>
            </tr>
<?php if ($tempateWithCountries) :?>
            <tr>
              <td colspan="2">
                <div class='feldtitel'>Select countries:</div>
                <select id="select_default_country" data-placeholder="Select countries ..." class="chosen-select" style="width: 73%" multiple size=3>
          <?php
          foreach($lang_countries as $k => $v):
            if (!in_array($k,$group)) continue;
          ?>
                  <option value="<?= $k ?>"><?= $v ?></option>

          <?php 		endforeach; ?>
                </select>
              </td>
            </tr>
<?php endif ?>
            <tr>
                <td colspan="2" id="codeItem" class="codeItem">
                  <div class='feldtitel1'>Embed code for websites and blogs:</div>
                  <textarea id="sharecode" class="urlTextarea"></textarea>
                </td>
            </tr>
          </table>
          <div id="preview_text">Embed preview</div>
        </div>
      </div>
    </div>
    <div id="canvas-wrapper"></div>
  </body>
</html>
