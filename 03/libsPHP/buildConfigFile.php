<?php


function buildConfigFile($json,$project,$language) {

   $config = json_decode($json, TRUE);

   if ($project !== NULL) {
      $langWizard = NULL;
      if (file_exists(PROJECTS_DIR . '/lang/'.$project.'/lang_'.$language.'.json')) {
         $langWizard = json_decode(file_get_contents(PROJECTS_DIR . '/lang/'.$project.'/lang_'.$language.'.json'), TRUE);
      } else {
         @include (PROJECTS_DIR . '/lang/'.$project.'/lang_'.$language.'.php');
      }
      $config = mergeLangWizard($language, $config, $langWizard);
   }

// add one-off settings for wizard projects (temporary solution)
  $includeProject = $config['project']['url'];
  if (file_exists(ONE_OFF_DIR . '/oneOffSettings/'.$includeProject.'.php')) {
    include (ONE_OFF_DIR . '/oneOffSettings/'.$includeProject.'.php');
    if (isset($oneOffSettingsTabs)) {
      foreach ($oneOffSettingsTabs as $tabkey => $tabValue) {
        $config['tabs'][$tabkey] = array_replace($config['tabs'][$tabkey],$tabValue);
      }
    }
    if (isset($oneOffSettingsCharts)) {
      $config['charts'] = array_replace_recursive($config['charts'], $oneOffSettingsCharts);
    }
  }

// add available languages
  $projectLanguages = json_decode(file_get_contents(PROJECTS_DIR . '/projectLanguages.json'), TRUE);
  $config['languages'] = isset($projectLanguages[$project]) ? $projectLanguages[$project] : array();

  return $config;

}


function mergeLangWizard($lang, $config, $trans) {
// merge translations from $trans with English text in $config
// and return $config with translations
  if (isset($config['en'])) {
    if (!is_string($trans) || $trans === '') {
      $config[$lang] = $config['en'];
    } else {
      $config[$lang] = $trans;
    }
  } else if (is_array($config)) {
    foreach ($config as $k => &$v) {
      $v = mergeLangWizard($lang, $v, @$trans[$k]);
    }
  }
  return $config;
}


