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


<!DOCTYPE html>
<html lang="<?=$language?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?= $lang['main_title'] ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">

	<link rel="icon" type="image/gif" href="/02resources/img/<?= $themeURL ?>/favicon.png">
	<link rel="stylesheet" type="text/css" href="/resources/normalize-css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="/resources/fullpage.js/jquery.fullPage.css">
	<link rel="stylesheet" type="text/css" href="/02resources/fonts/allOECDfonds.css" />

	<link rel="stylesheet" type="text/css" href="/03resources/css/wizardShort.css" />

	<script src='/resources/jquery/jquery.min.js'></script>
	<script src='/resources/modernizr/modernizr.js'></script>
	<script src='/resources/highcharts-release/highcharts.js'></script>
	<script src='/resources/fullpage.js/jquery.fullPage.js'></script>
	<script src="/resources/jquery-textfill/source/jquery.textfill.min.js"></script>

	<script src="/countryNames.js?vr=lang_countries&lg=<?= $language ?>" ></script>

	<script>
		var config 					  = <?= json_encode($config) ?>;
		var ISO3toFlags 			= <?= json_encode($ISO3toFlags) ?>;
		var lang 					    = <?= json_encode($language) ?>;
		var staticURL 				= <?= json_encode($staticURL) ?>;
	</script>


	<script src='/03resources/js/slide.min.js'></script>

<?php
	require(__DIR__."/analytics/".$themeURL.".php");
?>
				      
</head>
<body>
</body>
</html>
