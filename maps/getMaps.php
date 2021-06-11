<?php

function getMaps ($country=null,$type='shapes',$themeURL='oecd') {

  $themes = array ('oecd','ecb');
  if (!in_array($themeURL,$themes)) {
    $themeURL = 'oecd';
  }

  $countries = array ('bwa','gmb','mwi','nga','cities');
  if (!in_array(strtolower($country),$countries)) {
    $country = null;
  }

  if ($country == null) {
    if ($type=='shapes') {
      $map = __DIR__.'/json/'.$themeURL.'/world-shapes.geojson';
    } elseif ($type=='shapesWithEU') {
      $map = __DIR__.'/json/'.$themeURL.'/world-shapes1.geojson';
    } elseif ($type=='centroides') {
      $map = __DIR__.'/json/'.$themeURL.'/world-centroides.geojson';
    } elseif ($type=='centroidesList') {
      $map = __DIR__.'/json/'.$themeURL.'/world-centroides-list.json';
    } elseif ($type=='centroidesListEU') {
      $map = __DIR__.'/json/'.$themeURL.'/world-centroides-list1.json';
    } elseif ($type=='disputed') {
      $map = __DIR__.'/json/'.$themeURL.'/world-disputedLines.geojson';
    } elseif ($type=='config') {
      $map = __DIR__.'/json/'.$themeURL.'/featureReferenceCode.js';
    } else {
      $map = null;
    }
  } elseif ($country == 'cities') {
    if ($type=='shapes') {
      $map = __DIR__.'/json/'.$themeURL.'/world-shapes.geojson';
    } elseif ($type=='shapesWithEU') {
      $map = __DIR__.'/json/'.$themeURL.'/world-shapes1.geojson';
    } elseif ($type=='centroides') {
      $map = __DIR__.'/json/cities/centroides.geojson';
    } elseif ($type=='centroidesList') {
      $map = __DIR__.'/json/cities/centroides-list.json';
    } elseif ($type=='centroidesListEU') {
      $map = __DIR__.'/json/'.$themeURL.'/world-centroides-list1.json';
    } elseif ($type=='disputed') {
      $map = __DIR__.'/json/'.$themeURL.'/world-disputedLines.geojson';
    } elseif ($type=='config') {
      $map = __DIR__.'/json/'.$themeURL.'/featureReferenceCode.js';
    } else {
      $map = null;
    }
  } else {
    if ($type=='shapes') {
      $map = __DIR__.'/json/countries/'.strtoupper($country).'/shapes.geojson';
    } elseif ($type=='centroides') {
      $map = __DIR__.'/json/countries/'.strtoupper($country).'/centroides.geojson';
    } elseif ($type=='centroidesList') {
      $map = __DIR__.'/json/countries/'.strtoupper($country).'/centroides-list.json';
    } elseif ($type=='disputed') {
      $map = __DIR__.'/json/countries/'.strtoupper($country).'/disputedLines.geojson';
    } elseif ($type=='config') {
      $map = __DIR__.'/json/countries/'.strtoupper($country).'/featureReferenceCode.js';
    } else {
      $map = null;
    }
  }

  return $map;
}

