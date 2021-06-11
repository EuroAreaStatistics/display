<?php

// fill data position when values are not available for all indicators

//define dataSet
//$startTime
//$endTime
//ini_set ('display_errors','ON');
require_once('CalcJSON.php');

$tabID = $config['project']['tabs'][$page];

if (isset($config['tabs'][$tabID]['barsTableSorting'])) {
  $sorting = $config['tabs'][$tabID]['barsTableSorting'];
} else {
  $sorting = NULL;
}


if ($chartDisplay == 'BarsDiamondsTime') {
  foreach ($ConfigProject as $key => $projectChart) {
    $ConfigProject[$key]['path'] = getPHParray($projectChart);
  }
} else {
  foreach ($ConfigProject as $key => $projectChart) {
    $ConfigProject[$key]['path'] = getPHParrayLatest($projectChart);
  }
}

function getPHParray($Chart) {
  $t = new DC();
  $t-> fromJSON(json_encode($Chart['data']));
  $t->orderDimensions(array('YEAR', 'LOCATION'));  //MainDimension - SubDimension
  $a = $t->toPHParray(array('YEAR','LOCATION'));
  return $a;
}

function getPHParrayLatest($Chart) {
  $t = new DC();
  $t->fromJSON(json_encode($Chart['data']));
  return $t->toLatestValues(array('LOCATION'), 'YEAR');
}

function bd_nice_number($n) {
  global $lang;

  // first strip any formatting;
  $n = (0+str_replace(",","",$n));

  // is this a number?
  if(!is_numeric($n)) return false;

  // now filter it;
  if($n>1000000000000) return round(($n/1000000000000),1).' '.$lang['trillion'];
  else if($n>1000000000) return round(($n/1000000000),1).' '.$lang['billion'];
  else if($n>1000000) return round(($n/1000000),1).' '.$lang['million'];
  else if($n>1000) return round(($n/1000),1).' '.$lang['thousand'];

  return number_format($n);
}

function collectKeys($Chart, $ConfigProject) {
  $keys = array();
  foreach ($Chart as $i) {
    foreach ($ConfigProject[$i]['path'] as $key => $v) {
      if (!in_array($key, $keys)) {
        $keys[] = $key;
      }
    }
  }
  return $keys;
}
function exponent2scale($exponent) {
  global $lang;
  $numericNames = array('thousand', 'million', 'billion', 'trillion');

  if ($exponent > 0 && isset($numericNames[$exponent-1])) {
    return array('exponent' => $exponent, 'name' => $lang[$numericNames[$exponent-1]]);
  } else {
    return array();
  }
}

function calculateScale($dataSet, $absMin = null) {
  /* Calculate the exponent to scale numbers
   * from the absolute minimum nonzero value ($absMin)
   * and returns the exponent (if necessary) and absMin
   * (can be called multiple times on different dataSets by passing
   *  absMin to subsequent calls)
   */

  $exponent = 0;

  foreach ($dataSet as $v) {
    $a = abs(is_array($v) ? $v[1] : $v);
    if ($a > 0) {
      if (!isset($absMin) || $absMin > abs($a)) {
        $absMin = abs($a);
      }
    }
  }

  if ($absMin !== null) {
    $exponent = floor(floor(log10($absMin))/3);
  }

  $scale = exponent2scale($exponent);
  $scale['absMin'] = $absMin;
  return $scale;
}

function scaleData($dataSet, $exponent) {
  /* * Numbers are scaled by 1000^(-exponent)
   * $absMin is scaled as follows:
   *       999 ->  999
   *     1 000 ->    1
   *   999 999 ->  999.999
   * 1 000 000 ->    1
   */
  $factor = pow(1000, $exponent);
  foreach ($dataSet as &$v) {
    if (is_array($v)) {
      if ($v[1] !== null) {
        $v[1] = $v[1] / $factor;
      }
    } else if ($v !== null) {
      $v = $v / $factor;
    }
  }
  return $dataSet;
}

function barsBarsDataIndicator($countryNames, $indicator, $scale) {
  $dataSet            = $indicator['path'];
  $data               = array();
  $data['title']      = $indicator['title'];
  $data['definition'] = $indicator['definition'];
  $data['decimals']   = isset($indicator['options']['tooltipDecimals']) ? $indicator['options']['tooltipDecimals'] : 0;
  if ($indicator['options']['tooltipUnit'] != null) {
    $data['unit']     = $indicator['options']['tooltipUnit'];
  }

  if (isset($scale['name']) && strpos($data['unit'], '%') === FALSE) {
    $dataSet = scaleData($dataSet, $scale['exponent']);
    $data['unit'] = $scale['name'] . ' ' . $data['unit'];
    $data['decimals'] = 0;
  }

  $data['yearMax']    = null;
  $data['columnMax']  = 0;
  $data['columnMin']  = 0;

  foreach ($dataSet as $key => $v) {
    if ($data['yearMax'] < $v[0] ) {
      $data['yearMax'] = $v[0];
    }
    if (isset($indicator['options']['maximum']) && $indicator['options']['maximum'] != null) {
      $data['columnMax'] = $indicator['options']['maximum'];
    } elseif ($data['columnMax'] < $v[1] ) {
      $data['columnMax'] = $v[1];
    }
    if (isset($indicator['options']['minimum']) && $indicator['options']['minimum'] != null) {
      $data['columnMin'] = $indicator['options']['minimum'];
    } elseif ($v[1] != null && $data['columnMin'] > $v[1] ) {
      $data['columnMin'] = $v[1];
    }
  }


  $data['range'] = $data['columnMax'] - $data['columnMin'];

  foreach ($countryNames as $k) {
    $v = isset($dataSet[$k]) ? $dataSet[$k] : array(null, null);

    if ($data['columnMin'] < 0  &&  $v[1] < 0) {
      if ($v[1] < $data['columnMin'] ) {
        $sizeV = $data['columnMin'];
      } elseif ($v[1] > $data['columnMax'] ) {
        $sizeV = $data['columnMax'] - $data['columnMin'];
      } else {
        $sizeV = $v[1];
      }
    } else if ($data['columnMin'] < 0  &&  $v[1] >= 0) {
        $sizeV = $v[1];
    } else {
      if ($v[1] < $data['columnMin'] ) {
        $sizeV = 0;
      } elseif ($v[1] > $data['columnMax'] ) {
        $sizeV = $data['columnMax'] - $data['columnMin'];
      } else {
        $sizeV = $v[1] - $data['columnMin'];
      }
    }

    if ($v[1] === null) {
      $data['label'][$k] = '&#x2013;'; // EN DASH
      $data['width'][$k] = 0;
    } else {
      $data['label'][$k] = round($v[1], $data['decimals']);
      if ($v[0] != $data['yearMax']) {
        $data['label'][$k] .= '*';
        $data['tooltip'][$k] = $v[0];
      }
      $data['width'][$k] = abs(round($sizeV,$data['decimals'])/$data['range'])*100;
    }

    if ($data['columnMin'] < 0  &&  $v[1] < 0) {
      $data['position'][$k] = ((abs($data['columnMin']) - abs(round($sizeV,$data['decimals']))) /$data['range'])*100;
      $data['posText'][$k] = ((abs($data['columnMin']))/$data['range'])*100;
    } else if ($data['columnMin'] < 0  &&  $v[1] >= 0) {
      $data['position'][$k] = ((abs($data['columnMin']))/$data['range'])*100;
      $data['posText'][$k] = ((abs($data['columnMin']) + abs(round($sizeV,$data['decimals'])))/$data['range'])*100;
    } else {
      $data['position'][$k] = 0;
      $data['posText'][$k] = $data['width'][$k];
    }
  }

  $stringLength = 0;
  foreach ($data['label'] as $val) {
    if ($val!= null && strlen($val) > $stringLength) {
      $stringLength = strlen($val);
    }
  }
  $data['labelLength'] = ($stringLength*10)+100;

  return $data;
}

function barsBarsData($countryNames, $Chart, $ConfigProject, $titleDisplay, $exponent) {
  if (isset($exponent)) {
    $overallScale = exponent2scale($exponent);
  }
  if (!isset($overallScale['name'])) {
    $overallScale = array();
    foreach ($Chart as $i) {
      $scale = calculateScale($ConfigProject[$i]['path']);
      if (isset($scale['name'])) {
        if (! (isset($overallScale['name']) && $overallScale['exponent'] <= $scale['exponent'])) {
          $overallScale = $scale;
        }
      } else {
        $overallScale = array();
        break;
      }
    }
  }
  $data = array();
  foreach ($Chart as $i) {
    $data[$i] = barsBarsDataIndicator($countryNames, $ConfigProject[$i], $overallScale);
  }
  if ($titleDisplay) {
    $data['select'] = array_map(function($e) { return $e['title']; }, $ConfigProject);
  }
  return $data;
}

function barsDiamondsIndicatorsData($Chart, $ConfigProject) {
  $data = array();

  $data['title']      = '';
  $data['definition'] = '';

  $dataSet = array();
  $dataSet[0] = $ConfigProject[$Chart[0]]['path'];
  $dataSet[1] = $ConfigProject[$Chart[1]]['path'];

  $decimals = array();
  $decimals[0] = $ConfigProject[$Chart[0]]['options']['tooltipDecimals'];
  $decimals[1] = $ConfigProject[$Chart[1]]['options']['tooltipDecimals'];

  $scale = calculateScale($dataSet[0]);
  $scale = calculateScale($dataSet[1], $scale['absMin']);
  if (isset($scale['name'])) {
    $dataSet[0] = scaleData($dataSet[0], $scale['exponent']);
    $dataSet[1] = scaleData($dataSet[1], $scale['exponent']);
    $scaleName = $scale['name'] . ' ';
  } else {
    $scaleName = '';
  }

  $yearMax = array();
  for ($i=0;$i<=1;$i++){
    $yearMax[$i] = 0;
    foreach ($dataSet[$i] as $k => $v) {
      if ($yearMax[$i] < $v[0]) {
        $yearMax[$i] = $v[0];
      }
    }
  }

  $columnMax = 0;
  foreach ($dataSet as $d) {
    foreach ($d as $k => $v) {
      if ($columnMax < $v[1]) {
        $columnMax = $v[1];
      }
    }
  }
  $columnMin = 0;

  $data['barsSeries']   = $ConfigProject[$Chart[0]]['title'];
  $data['circleSeries'] = $ConfigProject[$Chart[1]]['title'];

  $data['barsSeriesDef']  = $ConfigProject[$Chart[0]]['definition'];
  $data['circleSeriesDef']  = $ConfigProject[$Chart[1]]['definition'];

  if ($ConfigProject[$Chart[0]]['options']['tooltipUnit'] != null) {
    $data['barsSeriesUnit']  = ', ' . $scaleName . $ConfigProject[$Chart[0]]['options']['tooltipUnit'].', '.$yearMax[1];
  }

  if ($ConfigProject[$Chart[1]]['options']['tooltipUnit'] != null) {
    $data['circleSeriesUnit']   = ', ' . $scaleName . $ConfigProject[$Chart[1]]['options']['tooltipUnit'].', '.$yearMax[1];
  }

  foreach ($dataSet[0] as $country => $value) {

    $data['barsValue'][$country]  = round ($dataSet[0][$country][1], $decimals[0]);
    $data['circleValue'][$country]  = round ($dataSet[1][$country][1], $decimals[1]);

    $data['barsWidth'][$country]  = ((round ($dataSet[0][$country][1], $decimals[0])/$columnMax)-($columnMin/$columnMax))*200;

    if ( ($dataSet[1][$country][1]/$columnMax)-($columnMin/$columnMax) >=0 ) {
      $data['circlePos'][$country] = ((round ($dataSet[1][$country][1], $decimals[1])/$columnMax)-($columnMin/$columnMax))*200;
    } else {
      $data['circlePos'][$country] = null;
    }
  }

  return $data;
}

function barsDiamondsTimeData($Chart, $ConfigProject) {
  $data = array();

  $decimals = $ConfigProject[$Chart[0]]['options']['tooltipDecimals'];

  $dataSet = $ConfigProject[$Chart[0]]['path'];

  $i=0;
  foreach($dataSet as $year => $values) {
    $seriesYears[$i] = $year;
    $i=$i+1;
  }

  $barsSeries = $seriesYears[count($seriesYears)-1];
  $circleSeries = $seriesYears[0];

  $scale = calculateScale($dataSet[$barsSeries]);
  $scale = calculateScale($dataSet[$circleSeries], $scale['absMin']);
  if (isset($scale['name'])) {
    $dataSet[$barsSeries] = scaleData($dataSet[$barsSeries], $scale['exponent']);
    $dataSet[$circleSeries] = scaleData($dataSet[$circleSeries], $scale['exponent']);
    $scaleName = $scale['name'] . ' ';
  } else {
    $scaleName = '';
  }

  if ($ConfigProject[$Chart[0]]['options']['tooltipUnit'] != null) {
    $dataUnit   = ', ' . $scaleName . $ConfigProject[$Chart[0]]['options']['tooltipUnit'];
  }

  $data['title']      =  $ConfigProject[$Chart[0]]['title'];
  $data['definition'] = $ConfigProject[$Chart[0]]['definition'].$dataUnit;

  $columnMax = max(max($dataSet[$barsSeries]), max($dataSet[$circleSeries]));
  $columnMin = 0;


  $data['barsSeries']   = $barsSeries;
  $data['circleSeries'] = $circleSeries;

  foreach ($dataSet[$barsSeries] as $country => $value) {

    $data['barsValue'][$country]  = round ($dataSet[$barsSeries][$country], $decimals);
    $data['circleValue'][$country]  = round ($dataSet[$circleSeries][$country], $decimals);

    $data['barsWidth'][$country]  = ((round($dataSet[$barsSeries][$country], $decimals)/$columnMax)-($columnMin/$columnMax))*200;

    if ( ($dataSet[$circleSeries][$country]/$columnMax)-($columnMin/$columnMax) >=0 ) {
      $data['circlePos'][$country] = ((round($dataSet[$circleSeries][$country], $decimals)/$columnMax)-($columnMin/$columnMax))*200;
    } else {
      $data['circlePos'][$country] = null;
    }
  }

  return $data;
}

if ($chartDisplay == 'BarsBarsIndicators' || $chartDisplay == 'BarsBarsIndicatorsSimple') {

  $countryNames = collectKeys($Chart, $ConfigProject);
  $tab = $config['tabs'][$config['project']['tabs'][$page]];
  $data = barsBarsData($countryNames, $Chart, $ConfigProject, !empty($tab['titleDisplay']), isset($tab['exponent']) ? $tab['exponent']: NULL);
  $charts = count($Chart);

} else if ($chartDisplay == 'BarsDiamondsIndicators') {

  $data = barsDiamondsIndicatorsData($Chart, $ConfigProject);

} else { // $chartDisplay == 'BarsDiamondsTime'

  $data = barsDiamondsTimeData($Chart, $ConfigProject);

}


?>

<script>

<?php if ($chartDisplay == 'BarsDiamondsIndicators' || $chartDisplay == 'BarsDiamondsTime') : ?>

  $(function() {
    $("#results").tablesorter({
      sortList: [[3,1]],
      headers:{
        1:{sorter:false}
      },
      textExtraction: function (node) {
        var txt = $(node).text();
        txt = txt.replace('\u2014', ''); // EM DASH
        txt = txt.replace('*', '');
        return txt;
      }
    });
  });

<?php elseif ($chartDisplay == 'BarsBarsIndicators'|| $chartDisplay == 'BarsBarsIndicatorsSimple') : ?>

  var charts = <?= json_encode($charts) ?>;
  var sorting = <?= json_encode($sorting) ?>;
  var sortingChart = 1;
  var sortingOrder = 1;

  if (sorting != null) {
    sortingChart = sorting[0];
    sortingOrder = sorting[1];
  }

  $(function() {
    $("#results").tablesorter({
      sortList: [[sortingChart,sortingOrder]],
      headers: {
        1: { sorter: 'digit' },
        2: { sorter: 'digit' },
        3: { sorter: 'digit' },
        4: { sorter: 'digit' },
      },
      textExtraction: function (node) {
        var txt = $(node).text();
        txt = txt.replace('\u2014', ''); // EM DASH
        txt = txt.replace('*', '');
        return txt;
      }
    });
  });

<?php endif ?>

</script>

<?php

if ($chartDisplay == 'BarsDiamondsIndicators' || $chartDisplay == 'BarsDiamondsTime') {
  include 'barsTable/barsDiamonds.php';
}
if ($chartDisplay == 'BarsBarsIndicators') {
  include 'barsTable/barsBars.php';
}
if ($chartDisplay == 'BarsBarsIndicatorsSimple') {
  include 'barsTable/barsBarsSimple.php';
}
