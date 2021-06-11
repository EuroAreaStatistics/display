<?php

//ini_set ('display_errors','ON');

$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : 'en';
$country = (isset($_REQUEST['cr']) && preg_match('/^[A-Za-z0-9-]*$/',$_REQUEST['cr'])) ? $_REQUEST['cr'] : 'oecd';
$project = (isset($_REQUEST['wizardProject'])&& preg_match('/^[a-z0-9-]*$/',$_REQUEST['wizardProject'])) ? $_REQUEST['wizardProject'] : NULL;

$shareLanguagesProject = null;
$templateList = null;
$config = null;
$page = 0;

require_once(__DIR__.'/../../countryNames/getCountryNames.php');
$lang_countries = getCountryNames ($language,$themeURL);
$lang_countries_titles = getCountryNames ($language,$themeURL,'long');

require_once(__DIR__.'/../../commonLabels/getCommonLabels.php');
$lang = getCommonLabels($language,$themeURL);

include(__DIR__.'/../'.$themeURL.'/lang_groups.php');
