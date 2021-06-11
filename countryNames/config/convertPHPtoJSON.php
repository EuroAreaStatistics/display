<?php

ini_set ('display_errors','ON');

require_once('../../../02projects/oecd/dataJSON/dataFetcherPath.php');
require_once('CalcJSON.php');
require_once('convertToWizardFunctions.php');

$sourceDirectory= __DIR__.'/../langCountries';
$files = DirectoryFiles ($sourceDirectory);

$groups = array (
 'afr' => 'Africa',
 'average' => 'Country average',
 'dac' => 'DAC Total',
 'lac7' => 'Latin America*',
 'oecd' => 'OECD',
 'eu27' => 'European Union',
 'eur' => 'Euro Area',
 'ea17' => 'Euro Area',
 'eu' => 'European Union',
 'e27' => 'European Union',
 'g20' => 'G20',
 'g7' => 'G7',
 'global' => 'Global',
 'wld' => 'OECD',
);

$groupsNew = array (
 'dac' => 'DAC Total',
 'oecd' => 'OECD',
 'eur' => 'Euro Area',
 'eu' => 'European Union',
 'g20' => 'G20',
 'g7' => 'G7',
);



foreach ($files as $file) {
  include ($sourceDirectory.'/'.$file);

  $countriesNew = array();
  $groupsCrNew = array();

  $fileName = explode(".",$file);
  $fileCat = explode("_",$fileName[0]);
  
  if ($fileCat[0] == 'langCountries') {
    foreach ($lang_countries as $code => $name) {
      if (!array_key_exists($code, $groups) && $name != null) {
        $countriesNew[$code] = $name;
      } elseif (array_key_exists($code, $groupsNew)) {
        $groupsCrNew[$code] = $name;
      }
    }
    file_put_contents($fileName[0].'.json', json_encode($countriesNew,JSON_PRETTY_PRINT) );
    file_put_contents('langCountryGroups_'.$fileCat[1].'.json', json_encode($groupsCrNew,JSON_PRETTY_PRINT) );
  } else {

    foreach ($lang_countries_long as $code => $name) {
      if (!array_key_exists($code, $groups)) {
        $countriesNew[$code] = $name;
      }
    }
    file_put_contents($fileName[0].'.json', json_encode($countriesNew,JSON_PRETTY_PRINT) );

  }


  unset($countriesNew);
  unset($lang_countries);

}


//echo'<pre>';
//print_r($files);
//die();
//
