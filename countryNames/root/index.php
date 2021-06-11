<?php

//ini_set ('display_errors','ON');

$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : 'en';
$themeURL = (isset($_REQUEST['th']) && preg_match('/^[a-z]*$/',$_REQUEST['th'])) ? $_REQUEST['th'] : 'oecd';
$mode = (isset($_REQUEST['mode']) && preg_match('/^[a-z]*$/',$_REQUEST['mode'])) ? $_REQUEST['mode'] : 'standard';
$variable = (isset($_REQUEST['vr']) && preg_match('/^[a-zA-Z_]*$/',$_REQUEST['vr'])) ? $_REQUEST['vr'] : 'countryNames';

include __DIR__.'/../getCountryNames.php';

$lang_countries = getCountryNames ($language,$themeURL,$mode);

header('Content-Type: application/javascript');
echo ("var ".$variable." = ".json_encode($lang_countries).";");
