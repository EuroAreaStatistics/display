<?php

$ConfigLangMain = array(
// fallback language in case of missing translations
  'fallback' => 'en',

// template string for output file, %s will be replaced with the current language
  'outputFile' => '../langCountriesNew/langCountries_%s.php',

// input files
//   variable: name of the PHP variable to write
//   key: column to use as key for array
//   sortKeys: which columns to sort on (-1 means the translated string itself),
//             an empty array means no sorting
  'inputFiles' => array(

    'lang_countries_master.csv' => array('variable' => 'lang_countries',
                                         'key'      => 'ISO',
                                         'sortKeys' => array('Sort', -1)),

  ),

// map language to locale for sorting
// entry can also be an integer column in the CSV file
// list available locales with:
//    locale -c LC_COLLATE -a
  'localeMap' => array(
    'en' => 'en_US.UTF-8',
    'fr' => 'fr_FR.UTF-8',
    'es' => 'es_ES.UTF-8',
    'de' => 'de_DE.UTF-8',
    'cn' => 'zh_CN.UTF-8',
    'jp' => 'ja_JP.UTF-8',
    'ru' => 'ru_RU.UTF-8',
    'pl' => 'pl_PL.UTF-8',
    'pt' => 'pt_PT.UTF-8',
    'it' => 'it_IT.UTF-8',
    'ko' => 'ko_KR.UTF-8',
    'bg' => 'bg_BG.UTF-8',
    'cs' => 'cs_CZ.UTF-8',
    'da' => 'da_DK.UTF-8',
    'el' => 'el_GR.UTF-8',
    'et' => 'et_EE.UTF-8',
    'fi' => 'fi_FI.UTF-8',
    'hr' => 'hr_HR.UTF-8',
    'hu' => 'hu_HU.UTF-8',
    'lt' => 'lt_LT.UTF-8',
    'lv' => 'lv_LV.UTF-8',
    'mt' => 'mt_MT.UTF-8',
    'nl' => 'nl_NL.UTF-8',
    'ro' => 'ro_RO.UTF-8',
    'sk' => 'sk_SK.UTF-8',
    'sl' => 'sl_SI.UTF-8',
    'sv' => 'sv_SE.UTF-8',
  ),

);

require_once(__DIR__.'ParseLangMain.php');

$x = new ParseLangMain();
$x->generate();

echo('langCountries parsed');
