<?php

function mapColors($themeURL,$colorset='default') {

  $colors = json_decode(file_get_contents(__DIR__.'/'.$themeURL.'.json'),true);

  if ($colorset=='default') {
    return $colors['Green8'];
  } else {
    return $colors[$colorset];
  }
  
}
