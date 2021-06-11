<?php

//ini_set ('display_errors','ON');
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/chartTypes');
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/headerFooter');
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/libsPHP');

require (__DIR__.'/api/apiClient.php');

$chartTypes = json_decode(file_get_contents(__DIR__.'/libsPHP/chartTypes.json'),true);
$ISO3toFlags = json_decode(file_get_contents(__DIR__.'/../flags/ISO3toFlags.json'),TRUE);

$BrowserCheck = "
    <!--[if lt IE 8]>
      <p class='chromeframe'>You are using an <strong>outdated</strong> browser. Please <a href='http://browsehappy.com/'>upgrade your browser</a> or <a href='http://www.google.com/chromeframe/?redirect=true'>activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->
";

$BrowserCheckIE9 = "
    <!--[if lt IE 9]>
      <p class='chromeframe'>You are using an <strong>outdated</strong> browser. Please <a href='http://browsehappy.com/'>upgrade your browser</a> or <a href='http://www.google.com/chromeframe/?redirect=true'>activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->
";

?>
<!doctype html>
<html lang="<?=$language?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?= $lang['main_title'] ?></title>
<?php if (isset($lang['page_keywords'])) : ?>
  <meta name="keywords" content="<?= htmlspecialchars($lang['page_keywords']) ?>">
<?php endif ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">

<?php if ($themeURL == 'ecb') : ?>
  <meta name="description" content="">
<?php elseif ($themeURL == 'oecd') :
  $metaTagDescription = "Ready for the big picture? This OECD data visualisation tool compares countries accross several indicators.";
?>
  <meta name="description" content="<?= $metaTagDescription ?>">

  <meta property="og:title" content="<?= $lang['main_title_compare'] ?> - <?= $config['project']['title'][$language] ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?= $liveURL ?>/<?= $project ?>?lg=<?= $language ?>" />
  <meta property="og:image" content="http:<?= $liveURL ?>/screenshot.png?type=<?= time() ?>&mode=1&project=<?= urlencode($project) ?>&lg=<?= urlencode($language) ?>" />
  <meta property="og:description" content="<?= $metaTagDescription ?>" />

  <meta name="twitter:card" content="summary_large_image" >
  <meta name="twitter:site" content="@OECD" >
  <meta name="twitter:creator" content="@OECD" >
  <meta name="twitter:title" content="<?= $lang['main_title_compare'] ?> - <?= $config['project']['title'][$language] ?>" >
  <meta name="twitter:description" content="<?= $metaTagDescription ?>" >
  <meta name="twitter:image:src" content="http:<?= $liveURL ?>/screenshot.png?type=<?= time() ?>&mode=1&project=<?= urlencode($project) ?>&lg=<?= urlencode($language) ?>" >
<?php endif ?>

  <link rel="icon" type="image/gif" href="/img/<?= $themeURL ?>/favicon.png">

  <link rel="stylesheet" type="text/css" href="<?= $liveURL ?>/resources/normalize-css/normalize.css" />
<?php if ($themeURL == 'oecd' || $themeURL == 'default') : ?>
  <link rel="stylesheet" type="text/css" href="<?= $staticURL ?>/fonts/allOECDfonds.css" />
<?php elseif ($themeURL == 'ecb') : ?>
  <link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,800&amp;subset=latin,greek-ext,cyrillic-ext,greek,latin-ext,cyrillic">
<?php endif ?>

<?php foreach ($chartTypes[$chartType]['resourcesCSS'] as $cssResource): ?>
  <link rel="stylesheet" type="text/css" href="<?= $liveURL ?>/resources/<?= $cssResource ?>">
<?php endforeach ?>
  <link rel="stylesheet" type="text/css" href="<?= $liveURL ?>/03resources/css/<?= $themeURL ?>.css" />
<?php foreach ($chartTypes[$chartType]['css'] as $cssFile): ?>
  <link rel="stylesheet" type="text/css" href="<?= $liveURL ?>/03resources/css/<?= $cssFile ?>" />
<?php endforeach ?>

  <script src='<?= $liveURL ?>/resources/jquery/jquery.min.js'></script>
  <script src='<?= $liveURL ?>/resources/modernizr/modernizr.js'></script>
<?php foreach ($chartTypes[$chartType]['resourcesJS'] as $jsResource): ?>
  <script src='<?= $liveURL ?>/resources/<?= $jsResource ?>'></script>
<?php endforeach ?>

  <script>

    var lang_labels             = <?= json_encode($lang) ?>;
    var urlcountry              = <?= json_encode($country) ?>;
    var urlcountries            = <?= json_encode($countries) ?>;

    var lang_countries          = <?= json_encode($lang_countries) ?>;
    var lang_countries_titles   = <?= json_encode($lang_countries_titles) ?>;
    var countries_offshore      = <?= json_encode($countriesOffshore) ?>;
    var project                 = <?= json_encode($project) ?>;
    var page                    = <?= json_encode($page) ?>;
    var embed                   = <?= json_encode($embed ?? FALSE) ?>;
    var lang                    = <?= json_encode($language) ?>;
    var template                = <?= json_encode($template) ?>;
    var baseURL                 = <?= json_encode($baseURL) ?>;
    var staticURL               = <?= json_encode($staticURL) ?>;
    var themeURL                = <?= json_encode($themeURL) ?>;
    var ISO3toFlags             = <?= json_encode($ISO3toFlags) ?>;

    var secondVisit             = <?= json_encode($secondVisit) ?>;

//fourLines
    var Chart                   = <?= json_encode($Chart) ?>;
    var maxSeries               = <?= json_encode(isset($maxSeries) ? $maxSeries : 4) ?>;

    var wizardConfig            = <?= json_encode($config) ?>;
    var navTabs                 = wizardConfig.project.tabs.length;

//mapWizardStandard
    var ChartsWithData          = wizardConfig.tabs[wizardConfig.project.tabs[page]].charts.length;

    if (typeof Highcharts !== 'undefined') {
      Highcharts.setOptions({lang: {
        thousandsSep: "\u00a0",
        months: Array.apply(null, Array(12)).map(function(_, i) { return window.lang_labels['month'][i+1]; })
      }});
      Highcharts.dateFormats = {
        Q: function (timestamp) {
          var date = new Date(timestamp),
              month = date.getMonth();
          return Math.floor(month/3+1);
        }
      };
      if (themeURL == 'ecb') {
        Highcharts.setOptions({lang: { numericSymbols: ['M', 'MM', 'BN', 'T', 'P', 'E'] }});
      }
    }

<?php if ($themeURL == 'oecd') : ?>
    $.ajax({url:'/screenshot.png?mode=2&project=<?= urlencode($project) ?>&lg=<?= urlencode($language) ?>'});
<?php endif ?>



  </script>

<?php foreach ($chartTypes[$chartType]['js'] as $jsFile): ?>
  <script src='<?= $liveURL ?>/03resources/js/<?= $jsFile ?>'></script>
<?php endforeach ?>

  <script src='<?= $liveURL ?>/03resources/js/headerFooter.min.js'></script>

<?php
  require("analytics/".$themeURL.".php");
?>

</head>
<body>

<?php

  if ($pdf == TRUE){
    require (__DIR__.'/wizardPDF.php');
  } else {
    $BrowserCheck;
    if ($altHeader) {
      require("simpleHeader/".$themeURL.".php");
    } else {
      require("header.php");
    }

      require($chartTypes[$chartType]['template']);

    if ( substr($project,0,24) == 'african-economic-outlook') {
      require("OECDspecialHeaderFooter/AEOfooter.php");
    } else if ($altHeader) {

    } else {
      require("footer.php");
    }
  }

  require('about.php');
?>

</body>

</html>
