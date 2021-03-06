<?php

function getMaps ($country=null,$type='shapes',$themeURL='oecd',$variant) {

  $themes = array ('oecd','ecb');
  if (!in_array($themeURL,$themes)) {
    $themeURL = 'oecd';
  }

  $variants = ['', 'NUTS0', 'NUTS1', 'NUTS2', 'NUTS3'];
  if (!isset($variant) || !in_array($variant,$variants)) {
    $variant = $variants[0];
  }
  if ($variant !== '') {
    $variant = '-' . $variant;
  }

  if ($type=='shapes') {
    $map = __DIR__.'/json/'.$themeURL.'/world'.$variant.'-shapes.geojson';
  } elseif ($type=='shapesWithEU') {
    $map = __DIR__.'/json/'.$themeURL.'/world-shapes1.geojson';
  } elseif ($type=='centroides') {
    $map = __DIR__.'/json/'.$themeURL.'/world-centroides.geojson';
  } elseif ($type=='centroidesList') {
    $map = __DIR__.'/json/'.$themeURL.'/world'.$variant.'-centroides-list.json';
  } elseif ($type=='centroidesListEU') {
    $map = __DIR__.'/json/'.$themeURL.'/world-centroides-list1.json';
  } elseif ($type=='disputed') {
    $map = __DIR__.'/json/'.$themeURL.'/world-disputedLines.geojson';
  } elseif ($type=='config') {
    $map = __DIR__.'/json/'.$themeURL.'/featureReferenceCode.json';
  } else {
    $map = null;
  }

  return $map;
}

