<?php

//sub tabs for complex map wizard projects
function getSubTabs ($config) {
  global $language, $page;

  $tabID = $config['project']['tabs'][$page];
  $tab = $config['tabs'][$tabID];

  if (isset($tab['subTabs'])) {
    foreach ($tab['subTabs'] as $subID => &$subContent) {
      if (isset($subContent['charts'])) {
         foreach ($subContent['charts'] as $chartID => $subChart) {
            $chart = $config['charts'][$subChart['chart']];
            $name = $chartID;
            if (!isset($chart['data'])) {
              // empty data set
              $chart['data'] = array(
                'dimensions' => array('LOCATION', 'YEAR'),
                'keys' => array(array(), array()),
                'data' => array(),
              );
            }
            $tab['subTabs'][$subID]['ConfigSubCharts'][$name] = array(
              'title'      => $chart['title'][$language],
              'definition' => $chart['definition'][$language],
              'options'    => $chart['options'],
              'data'        => $chart['data'],
            );
         }
      }
    }
    return $tab['subTabs'];
  } else {
    return array();
  }
}

