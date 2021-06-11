<?php
ini_set('error_reporting', E_ALL);

$themeURL = 'ecb';
define('INSIGHTS_PROJECTS_REPO', __DIR__.'/../../../../cyc-ecb-projects-classic');
define('LANG_THEMES', __DIR__.'/../../../commonLabels/langThemes/'.$themeURL);
define('INSIGHTS_PROJECTS_DIR', INSIGHTS_PROJECTS_REPO.'/wizardProjects');

$insights = json_decode(file_get_contents(INSIGHTS_PROJECTS_DIR.'/items-insights.json'), TRUE);
$insight = array_slice($insights, -1)[0]['id'];
echo "insght $insight\n";
foreach (glob(LANG_THEMES.'/langTheme_*.json') as $theme) {
  $lang = explode('_', basename($theme, '.json'))[1];
  echo "$lang ";
  $file = INSIGHTS_PROJECTS_DIR.'/lang/'.$insight.'/lang_'.$lang.'.json';
  if (!file_exists($file)) {
    $file = INSIGHTS_PROJECTS_DIR.'/lang/'.$insight.'/lang_en.json';
  }
  $highlight = json_decode(file_get_contents($file), TRUE)['highlight'];
  $json = json_decode(file_get_contents($theme), TRUE);
  $json['insights_highlight'] = $highlight;
  file_put_contents($theme, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
}
echo "DONE\n";
