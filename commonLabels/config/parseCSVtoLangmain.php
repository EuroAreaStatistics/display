<?php

require_once('../../libsPHP/ParseLangMain.php');

$ConfigLangMain = array(
// fallback language in case of missing translations
  'fallback' => 'en',

// template string for output file, %s will be replaced with the current language
  'outputFile' => '../langmain_%s.php',

// input files
//   variable: name of the PHP variable to write
//   key: column to use as key for array
//   sortKeys: which columns to sort on (-1 means the translated string itself),
//             an empty array means no sorting
  'inputFiles' => array(

    'lang_master.csv' => array('variable' => 'lang',
                               'key'      => 'key',
                               'sortKeys' => array()),
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
  ),

);

$x = new ParseLangMain();
$x->generate();

$ConfigLangMain = array(
// fallback language in case of missing translations
  'fallback' => 'en',

// template string for output file, %s will be replaced with the current language
  'outputFile' => '../themes/oecd/langTheme_%s.php',

// input files
//   variable: name of the PHP variable to write
//   key: column to use as key for array
//   sortKeys: which columns to sort on (-1 means the translated string itself),
//             an empty array means no sorting
  'inputFiles' => array(

    'lang_master_oecd.csv' => array('variable' => 'langTheme',
                               'key'      => 'key',
                               'sortKeys' => array()),
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
  ),

);

$y = new ParseLangMain();
$y->generate();

echo('langmain parsed');
