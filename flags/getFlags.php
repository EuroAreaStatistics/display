<?php

function getFlags ($country=null) {

  $ISO3toFlags = json_decode(file_get_contents(__DIR__.'/ISO3toFlags.json'),TRUE);
  $country = strtoupper($country);

  if ($country == 'OECD') {
    $flag = __DIR__.'/specialFlags/OECD.png';
  } else if (isset($ISO3toFlags[$country])) {
    $flag = __DIR__.'/../resources/gosquared-flags/flags/flags-iso/flat/48/'.$ISO3toFlags[$country].'.png';
  } else {
    $flag = __DIR__.'/../02resources/img/1x25.png';
  }

  return $flag;
}

