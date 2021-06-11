<?php

//ini_set ('display_errors','ON');
require(__DIR__.'/../countryNames/getCountryNames.php');
require_once(__DIR__.'/../apiClientNotesData/API_project_get.php');
require_once(__DIR__.'/../commonLabels/getCommonLabels.php');
require_once(__DIR__.'/../03/libsPHP/themeSettings/'.$themeURL.'.php');

//add general template list and theme specific templates
require_once(mainFile('templateList.php','config'));
if (file_exists(mainFile('themes/'.$themeURL.'/templateListTheme.php','config'))) {
  require_once(mainFile('themes/'.$themeURL.'/templateListTheme.php','config'));
  $templateList = array_replace_recursive($templateList, $templateListTheme);
}

$lang_countries = getCountryNames ('en',$themeURL);
$lang = getCommonLabels('en',$themeURL);

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
  $embedLanguages = [];
}
array_push($embedLanguages,'en');


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
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title></title>
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
<?php include __DIR__.'/single.js'; ?>
    </script>
    <script>
      var model = <?= json_encode(array('lang' => $language, 'default_country' => $country, 'page' => (int)$page)) ?>;
      var project = <?= json_encode($project) ?>;
      var baseURL = <?= json_encode($baseURL) ?>;
      var embedURL = <?= json_encode($embedURL) ?>;
      var lang_labels = <?= json_encode($lang) ?>;
      var templates = <?= json_encode($templateList) ?>;
      var lang_languages = <?= json_encode($shareLanguages) ?>;
      var order_languages = <?= json_encode(array_keys($shareLanguages)) ?>;
      var lang_countries = <?= json_encode($lang_countries) ?>;
      var order_countries = <?= json_encode(array_keys($lang_countries)) ?>;
      var group = <?= json_encode($group) ?>;
      var embedLanguages = <?= json_encode($embedLanguages) ?>;
      var title = <?= json_encode($title) ?>;
      var wizardConfig = <?= json_encode($config) ?>;
      var sizes = <?= json_encode(array(array('width' => 1100, 'height' => 700),
                                        array('width' => 700, 'height' => 645),
                                        array('width' => 450, 'height' => 585))) ?>;
    </script>

<?php require(__DIR__.'/../03/analytics/'.$themeURL.'.php'); ?>

  </head>
  <body>
    <div id="embedContainer" class="resourceContainer">
      <div id="embedContent" class="resourceContent">
        <div id="storyPanel" class="bottomPanel">
          <div class="embedSwitch">
            Please select <a href="#">embed entire page</a> or continue below for embedding an individual chart.
          </div>
          <div>
            Select your preferences and paste the embed code into your website or blog.
          </div>
          <table id="sharetable">
            <tr>
              <td>
                <div class='feldtitel'>Select tab:</div>
                <select id="select_default_page">
                </select>
              </td>
              <td>
                <div class='feldtitel'>Select indicator:</div>
                <select id="select_default_indicator">
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <div class='feldtitel'>Select view:</div>
                <select id="select_default_template">
                </select>
              </td>
              <td>
                <div class='feldtitel'>Select image size:</div>
                <select id="select_size">
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <div class='feldtitel'>Select language:</div>
                  <select id="select_lang">
                  </select>
              </td>
              <td>
                <div id='custom-size-fields' style="display:none;">
                  Custom Size:
                  <input type="text" name="customX" id="customX" size="3" /> x
                  <input type="text" name="customY" id="customY" size="3" /> Pixel
                  <button id="custom-size-go">Set</button>
                </div>
              </td>
            </tr>
            <tr id="countries">
              <td colspan=2>
                <div class='feldtitel'>Select countries:</div>
                <select id="select_default_country" data-placeholder="Select countries ..." class="chosen-select" style="width: 73%" multiple size=3>
                </select>
              </td>
            </tr>
<?php if (FALSE): ?>
           <tr>
              <td colspan="2">
                <div class='feldtitel' >Add chart title:</div>
                <div id='titleInput'>
                  <input class="titleInput" type="text" name="customTitle" id="customTitle" value="<?= $config['charts'][$Charts[0]]['title'][$language] ?>">
                  <button id="custom-title-go">Set</button>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div class='feldtitel' >Add subtitle:</div>
                <div id='subTitleInput'>
                  <input class="titleInput" type="text" name="customSubTitle" id="customSubTitle" value="<?= $config['charts'][$Charts[0]]['definition'][$language] ?>">
                  <button id="custom-subtitle-go">Set</button>
                </div>
              </td>
            </tr>
<?php endif ?>
            <tr>
              <td colspan="2" class="codeItem">
                <button id="generate-embed">Generate embed code</button>
              </td>
            </tr>
            <tr>
                <td colspan="2" style="display:none" id="codeItem" class="codeItem">
                  <div class='feldtitel1'>Embed code for websites and blogs:</div>
                  <textarea id="sharecode" class="urlTextarea"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="display:none" id="error">
                  <div class='feldtitel1'>Error messages:</div>
                  <div id="errormsg"></div>
                </td>
            </tr>
          </table>
          <hr>
          <div id="preview_text">Embed preview</div>
        </div>
      </div>
    </div>
    <div id="canvas-wrapper"></div>
  </body>
</html>
