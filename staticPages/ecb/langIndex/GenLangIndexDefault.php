<?php
ini_set('error_reporting', E_ALL);

$themeURL = 'ecb';
define('PROJECTS_REPO', __DIR__.'/../../../../cyc-ecb-projects');
define('CORE_REPO', __DIR__.'/../../../../compare-your-country');
define('INSIGHTS_PROJECTS_REPO', __DIR__.'/../../../../cyc-ecb-projects-classic');
define('LANG_THEMES', __DIR__.'/../../../commonLabels/langThemes/'.$themeURL);

require PROJECTS_REPO.'/liveProjects.php';
require '../lang_groups.php';

require CORE_REPO.'/apiServer/buildConfigFile.php';

define('PROJECTS_DIR', PROJECTS_REPO.'/wizardProjects');
define('ONE_OFF_DIR', PROJECTS_DIR);
define('INSIGHTS_PROJECTS_DIR', INSIGHTS_PROJECTS_REPO.'/wizardProjects');

function readLangProject($project, $lg, $key) {
  $language = $lg;
  if ($project === 'financing-and-investment-dynamics2') $project = 'financing-and-investment-dynamics';
  $config = buildConfigFile(file_get_contents(PROJECTS_DIR . "/config/$project.json"), $project, $lg);
  $langProject = array($key => strip_tags($config['project']['title'][$lg]));
  return $langProject;
}

$lgs = array_keys($shareLanguages);

$projects = json_decode(file_get_contents('../projects.json'), TRUE);

foreach ($lgs as $lg) {
  echo "$lg ";
  $a = array();
  $theme = json_decode(file_get_contents(LANG_THEMES."/langTheme_$lg.json"), TRUE);
  
  foreach ($projects as $project => $data) {
    $subprojects = $data['projects'];
    $mainproject = array_shift($subprojects);
    $buttonTitle = str_replace('-', '_', $mainproject) . '_main';
    $langProject = readLangProject($mainproject, $lg, 'ButtonMainPageSub1');
    $a[$mainproject] = preg_replace('/.*\p{Zs}+\p{Pd}\p{Zs}+/u', '', $langProject['ButtonMainPageSub1']);
    if (isset($langProject['ButtonMainPage1'])) {
      $a[$mainproject.'_main'] = $langProject['ButtonMainPage1'];
    }
    if (!isset($theme[$buttonTitle])) {
      $a[$mainproject.'_main'] = $a[$mainproject];
    }
    foreach ($subprojects as $i => $subproject) {
      $langProject = readLangProject($subproject, $lg, 'ButtonMainPageSub'.($i+2));
      $a[$subproject] = preg_replace('/.*\p{Zs}+\p{Pd}\p{Zs}+/u', '', $langProject['ButtonMainPageSub'.($i+2)]);
    }
  }
  $a['financing-and-investment-dynamics'] = $theme['one_area'];
  $a['financing-and-investment-dynamics2'] = $theme['two_areas'];

  foreach ((array)glob(INSIGHTS_PROJECTS_DIR."/config-insights/*.json") as $file) {
    $insight = basename($file, ".json");
    if (in_array($insight, $projectsClassic)) {
      $langWizard = json_decode(@file_get_contents(INSIGHTS_PROJECTS_DIR."/lang/$insight/lang_$lg.json"), TRUE);
      if (!isset($langWizard) && $lg != 'en') {
        $langWizard = json_decode(@file_get_contents(INSIGHTS_PROJECTS_DIR."/lang/$insight/lang_en.json"), TRUE);
      }
      $a[$insight] = trim(strip_tags($langWizard['title']));
    }
  }

  ksort($a);
  $out = fopen("Index_master_$lg.php", 'w');
  fputs($out, "<?php\n\n\$langIndexDefault=array(\n'projects' => ".var_export($a, TRUE).");\n");
  
  fclose($out);
}

echo "DONE\n";
