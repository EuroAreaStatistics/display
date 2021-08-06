<?php

function getCountryNames ($language=null, $themeURL=null, $mode=null) {
  $language = strtolower($language);
  if (preg_match('/^[a-z]{2}$/', $language) == false) {
    $language = 'en';
  }

  $themes = ['oecd', 'ecb'];
  if (!in_array($themeURL, $themes)) {
    $themeURL = 'oecd';
  }

  if (file_exists(__DIR__.'/json/langCountriesTheme_'.$themeURL.'.json')) {
    $country_theme = json_decode(file_get_contents(__DIR__.'/json/langCountriesTheme_'.$themeURL.'.json'), true);
  } else {
    $country_theme = [];
  }

  $country_list = json_decode(file_get_contents(__DIR__.'/json/langCountries_en.json'), true);
  if (isset($country_theme['en'])) {
    $country_list = array_replace($country_list, array_intersect_key($country_theme['en'], $country_list));
  }
  if (file_exists(__DIR__.'/json/langCountries_'.$language.'.json')) {
    $country_list = array_replace($country_list, json_decode(file_get_contents(__DIR__.'/json/langCountries_'.$language.'.json'), true));
  }
  if (isset($country_theme[$language])) {
    $country_list = array_replace($country_list, array_intersect_key($country_theme[$language], $country_list));
  }

  $country_groups = json_decode(file_get_contents(__DIR__.'/json/langCountryGroups_en.json'), true);
  if (isset($country_theme['en'])) {
    $country_groups = array_replace($country_groups, array_intersect_key($country_theme['en'], $country_groups));
  }
  if (file_exists(__DIR__.'/json/langCountryGroups_'.$language.'.json')) {
    $country_groups = array_replace($country_groups, array_filter(json_decode(file_get_contents(__DIR__.'/json/langCountryGroups_'.$language.'.json'), true), function ($s) { return $s === ''; }));
  }
  if (isset($country_theme[$language])) {
    $country_groups = array_replace($country_groups, array_intersect_key($country_theme[$language], $country_groups));
  }

  $lang_countries = array_replace([
    'oecd' => $country_groups['oecd'],
  ], $country_list, [
    'eu' => $country_groups['eu'],
    'eur' => $country_groups['eur'],
  ]);

  if (file_exists(__DIR__.'/json/langCountriesISO_'.$themeURL.'.json')) {
    $country_ISO = json_decode(file_get_contents(__DIR__.'/json/langCountriesISO_'.$themeURL.'.json'), true);
    $country_ISO = array_column($country_ISO, 1, 0);
  } else {
    // default: map (lowercase) ISO codes to uppercase
    $country_ISO = array_keys($lang_countries);
    $country_ISO = array_combine($country_ISO, array_map('strtoupper', $country_ISO));
  }

  $country_long = [];
  if (file_exists(__DIR__.'/json/langCountriesLong_'.$language.'.json')) {
      $country_long = json_decode(file_get_contents(__DIR__.'/json/langCountriesLong_'.$language.'.json'), true);
  }

  if ($mode == 'all') {
    $lang_countries_long = array_replace($lang_countries, $country_long);
    $lang_countries = [
      'short' => $lang_countries,
      'long'  => $lang_countries_long,
      'order' => array_keys($lang_countries),
    ];
  } elseif ($mode == 'long') {
      $lang_countries = array_replace($lang_countries, $country_long);
  } elseif ($mode == 'ISO') {
      $lang_countries = $country_ISO;
  }

  return $lang_countries;
}
