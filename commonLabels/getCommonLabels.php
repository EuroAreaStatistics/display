<?php

function getCommonLabels ($language=null,$themeURL) {

  $language = strtolower($language);
  if (preg_match('/^[a-z]{2}$/',$language) == false) {
    $language = 'en';
  }

  $themeURL = strtolower($themeURL);
  if (preg_match('/^[a-z]*$/',$themeURL) == false) {
    return false;
  }

  $langThemePath = __DIR__.'/langThemes/'.$themeURL;

  if (file_exists(__DIR__.'/../../02projects/'.$themeURL.'/wizard-edit-repo/lang/langMain/langmain_'.$language.'.json')) {
    $lang = json_decode(file_get_contents(__DIR__.'/../../02projects/'.$themeURL.'/wizard-edit-repo/lang/langMain/langmain_'.$language.'.json'), TRUE);
  } else if (file_exists(__DIR__.'/langMain/langmain_'.$language.'.json')) {
    $lang = json_decode(file_get_contents(__DIR__.'/langMain/langmain_'.$language.'.json'), TRUE);
  } else {
    $lang = json_decode(file_get_contents(__DIR__.'/langMain/langmain_en.json'), TRUE);
  }

  if (file_exists($langThemePath.'/langTheme_'.$language.'.json')) {
    $langTheme = json_decode(file_get_contents($langThemePath.'/langTheme_'.$language.'.json'), TRUE);
  } else {
    $langTheme = json_decode(file_get_contents($langThemePath.'/langTheme_en.json'), TRUE);
  }

  if (isset($langTheme) && $langTheme != null) {
    $lang = array_replace_recursive ($lang,$langTheme);
  }

  if (file_exists(__DIR__.'/../../02projects/'.$themeURL.'/wizard-edit-repo/lang/themes/langTheme_'.$language.'.json')) {
    $langTheme = json_decode(file_get_contents(__DIR__.'/../../02projects/'.$themeURL.'/wizard-edit-repo/lang/themes/langTheme_'.$language.'.json'), TRUE);
    $lang = array_replace_recursive ($lang,$langTheme);
  }

  if ($language === 'en') {
    return $lang;
  } else {
    return array_replace_recursive(getCommonLabels('en', $themeURL), $lang);
  }
}
