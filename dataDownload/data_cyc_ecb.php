<?php

//ini_set ('display_errors','ON');
ini_set('memory_limit','285M');

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../vendors/phpexcel');
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../03/libsPHP');
require_once('CalcJSON.php');
require_once(__DIR__.'/../countryNames/getCountryNames.php');
require_once(__DIR__.'/../03/dataFetcher/ISOmap.php');
require_once(__DIR__.'/../apiClientNotesData/API_project_get.php');

define ('TEMP_PATH', __DIR__.'/../temp/data_output');
if (ACCESS=='preview' || ACCESS=='snapshots' ) {
  define('PROJECTS_DIR', __DIR__.'/../02projects/'.$themeURL.'/wizard-edit-repo/wizardProjects');
} else {
  define('PROJECTS_DIR', __DIR__.'/../02projects/'.$themeURL.'/wizardProjects');
}

$project = (isset($_GET['project'])&& preg_match('/^[a-z0-9-]*$/',$_GET['project'])) ? $_GET['project'] : NULL;
$tab = (isset($_GET['tab'])&& preg_match('/^[a-z0-9-]*$/',$_GET['tab'])) ? $_GET['tab'] : NULL;
$chart = (isset($_GET['chart'])&& preg_match('/^[a-z0-9._-]*$/i',$_GET['chart'])) ? $_GET['chart'] : NULL;

if($project === NULL) {
  die ("Error generating data file, no project set");
}

$input = API_project_get($project, "/project/$project/en");
if ($input === FALSE) {
  die ("Error generating data file, project not found");
}

$config = json_decode($input, TRUE);
if ($tab !== NULL && !isset($config['tabs'][$tab])) {
  die ("Error generating data file, tab not found");
}
if ($chart !== NULL && !isset($config['charts'][$chart])) {
  die ("Error generating data file, chart not found");
}

if ($tab !== NULL) {
  $tabs = array($tab);
} else {
  $tabs = array_keys($config['tabs']);
}

if ($chart != NULL) {
  $charts = array($chart);
} else {
  $charts = array();
  foreach ($tabs as $tab) {
    $charts = array_merge($charts, $config['tabs'][$tab]['charts']);
  }
}

$lang_countries = array_change_key_case(getCountryNames('en', $themeURL), CASE_UPPER);
$tabLabels = [];
if ($tab !== NULL && isset($config['tabs'][$tab]['labels'])) {
  $tabLabels = $config['tabs'][$tab]['labels'];
}

$data = array();
foreach ($charts as $chart) {
  $c = $config['charts'][$chart];
  if (isset($c['data']['dimensions']) && isset($c['data']['keys']) && !isset($c['data']['data'])) {
    $c['data'] = loadAjaxData($c, $project, '/data/' . $project . '/' . $tab . '_' . $chart . '_');
  }
  $d = array(
    'project' => $config['project']['title']['en'],
    'title' => $c['title']['en'],
    'unit' => $c['options']['tooltipUnit'],
  );
  if ($tab !== NULL) {
    $d['title'] = $config['tabs'][$tab]['title']['en'] . ', ' . $d['title'];
  }
  if (isset($c['definition']['en'])) {
    $d['definition'] = $c['definition']['en'];
  } else {
    $d['definition'] = $config['project']['definition']['en'];
  }
  if (isset($c['data']['url'])) {
    $d['url'] = $c['data']['url'];
  }
  $d['data'] = array();
  if (isset($c['data']['dimensions'])) {
    $keys = array_map(function ($labels) use($lang_countries, $tabLabels) {
      return array_map(function ($label) use($lang_countries, $tabLabels) {
        if (isset($lang_countries[$label])) {
          return $lang_countries[$label];
        } elseif (isset($tabLabels[$label])) {
          return strip_tags($tabLabels[$label]['en']);
        } else {
          return $label;
        }
      }, $labels);
    }, $c['data']['keys']);
    if (count($c['data']['dimensions']) != 2) {
      $row = array_merge($c['data']['dimensions'], array('Value'));
      if ($themeURL == 'ecb') {
        $row = ecbSdwCodesRow(NULL, $row, $c);
      }
      if (isset($c['options']['downloadColumns'])) {
        $row = array_map(function ($i) use($row) {
          return $row[$i];
        }, $c['options']['downloadColumns']);
      }
      $d['data'][] = $row;
      $cntr = new mdCounter(array_map('count', $keys));
      while (($index = $cntr->next()) !== FALSE) {
        $value = mdGet($c['data']['data'], $index);
        if ($value !== NULL) {
          $row = array_merge(array_map(function ($i, $k) use($keys) { return $keys[$i][$k]; }, array_keys($index), $index), array($value));
          if ($themeURL == 'ecb') {
            $row = ecbSdwCodesRow($index, $row, $c);
          }
          if (isset($c['options']['downloadColumns'])) {
            $row = array_map(function ($i) use($row) {
              return $row[$i];
            }, $c['options']['downloadColumns']);
          }
          $d['data'][] = $row;
        }
      }
    } else {
      $d['data'][] = array_merge(array('Country'), $keys[1]);
      foreach ($keys[0] as $row => $label) {
        $d['data'][] = array_merge(array($label), $c['data']['data'][$row]);
      }
      if ($themeURL == 'ecb') {
        $d = ecbSdwCodes($d, $c);
      }
    }
  }
  $data[] = $d;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="exportedData.xlsx"');
header('Cache-Control: max-age=0');

// PHPExcel
include 'PHPExcel.php';

// PHPExcel_Writer_Excel2007
include 'PHPExcel/Writer/Excel2007.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Excel Export");
$objPHPExcel->getProperties()->setLastModifiedBy("Excel Export");
$objPHPExcel->getProperties()->setTitle("Excel Export");
$objPHPExcel->getProperties()->setSubject("Excel Export");
$objPHPExcel->getProperties()->setDescription("Excel data export from data tool");

foreach ($data as $index => $table) {
  if ($index) {
    $objPHPExcel->createSheet();
  }
  $objPHPExcel->setActiveSheetIndex($index);
  $sheet = $objPHPExcel->getActiveSheet();


  $sheet->setCellValue('A1', html_entity_decode(strip_tags($table['title']), ENT_QUOTES|ENT_HTML401).', '.$table['unit'] );

  $sheet->setCellValue('A2', strip_tags($table['definition']));

  if (isset($table['url']) && $themeURL == 'ecb') {
    $sheet->setCellValue('A3', 'URL: '.$table['url']);
  }

  // leave one empty row
  $rowNo=5;

  foreach ($table['data'] as $zeile) {
      foreach ($zeile as $cellNo => $cell) {
          $sheet->setCellValueByColumnAndRow($cellNo, $rowNo, $cell);
      }
      $rowNo++;
      $columns = count($zeile)-1;
  }

  // Rename sheet
  $title = str_replace(array('*', ':', '/', '\\', '?', '[', ']'), '', html_entity_decode(strip_tags($table['title']), ENT_QUOTES|ENT_HTML401));
  $title = substr($title, 0, 31);
  $sheet->setTitle($title);

  $sheet->mergeCells('A1:'.PHPExcel_Cell::stringFromColumnIndex($columns).'1');
  $sheet->mergeCells('A2:'.PHPExcel_Cell::stringFromColumnIndex($columns).'2');
  if ($themeURL == 'ecb') {
    $sheet->mergeCells('A3:'.PHPExcel_Cell::stringFromColumnIndex($columns).'3');
  }

  for ($x=0;$x<=$columns;$x++) {
    $sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($x))->setAutoSize(true);
  }
}
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

$tempFile = TEMP_PATH.'/data'.getmypid().'.xlsx';

$objWriter->save($tempFile);
readfile($tempFile);
unlink($tempFile);

function loadAjaxData($c, $project, $path) {
  $files = [];
  $keyMap = [];
  $codes = [];
  $url = NULL;
  foreach ($c['data']['keys'][0] as $key) {
    $json = API_project_get($project, $path . strtolower($key));
    $data = json_decode($json, TRUE);
    if (isset($data['sdwCodes'])) {
      $codes[$key] = $data['sdwCodes'];
    }
    if (isset($data['url']) && !isset($url)) {
      $url = $data['url'];
    }
    $tmp = new DC();
    $tmp->fromJSON($json);
    foreach ($tmp->keyMap() as $dim => $keys) {
      if (!isset($keyMap[$dim])) {
        $keyMap[$dim] = $keys;
      } else {
        $keyMap[$dim] = array_values(array_unique(array_merge($keyMap[$dim], $keys)));
      }
    }
    $files[$key] = $tmp;
  }
  $data = new DC();
  $keyMap = array_merge(['Country' => $c['data']['keys'][0]], $keyMap);
  $data->initialize(array_keys($keyMap), array_values($keyMap));
  $data->updateValues(function ($v, $idx) use($files) {
    $key = $idx['Country'];
    unset($idx['Country']);
    return $files[$key]->atIndex($idx);
  });
  $data = $data->toArray();
  if (count($codes)) {
    $data['sdwCodes'] = $codes;
  }
  if (isset($url)) {
    $data['url'] = $url;
  }
  return $data;
}

function ecbSdwCodes($d, $c) {
  global $ISO2_ISO3, $ISO2_ISO3_alt, $ISO4217_ISO3;

  // reverse column order
  foreach ($d['data'] as &$values) {
    $label = array_shift($values);
    $values = array_merge(array($label), array_reverse($values));
  }
  // add SDW code
  if (isset($c['data']['sdwCodes'])) {
    foreach ($d['data'] as $row => &$values) {
      $sdw = NULL;
      if ($row) {
        $code = $c['data']['keys'][0][$row-1];
        if (isset($c['data']['sdwCodes'][$code])) {
          $sdw = $c['data']['sdwCodes'][$code];
        }
      } else {
        $sdw = 'Statistical Data Warehouse code';
      }
      array_splice($values, 0, 0, array($sdw));
    }
  } else if (isset($d['url'])) {
    $parts = array_slice(explode('/', parse_url($d['url'], PHP_URL_PATH)), -2);
    $parts = explode('.', implode('.', $parts));
    $sdwCodes = array();
    foreach ($parts as $i => $part) {
      if (preg_match('/^([^+]{2,3}[+]){2}/', $part)) {
        $sdwCodes = explode('+', $part);
        $codeIndex = $i;
        break;
      }
    }
    if (count($sdwCodes)) {
      $parts = array_map(function ($part) {
        return strpos($part, '+') === FALSE ? $part : '?';
      }, $parts);
      $ISOmap = array_merge($ISO2_ISO3, $ISO2_ISO3_alt);
      foreach ($d['data'] as $row => &$values) {
        $sdw = NULL;
        if ($row) {
          $code = $c['data']['keys'][0][$row-1];
          foreach ($sdwCodes as $s) {
            if ($code == $s ||
                (isset($ISOmap[$s]) && $code == $ISOmap[$s]) ||
                (isset($ISO4217_ISO3[$s]) && $code == $ISO4217_ISO3[$s])) {
              $parts[$codeIndex] = $s;
              $sdw = implode('.', $parts);
              break;
            }
          }
        } else {
          $sdw = 'Statistical Data Warehouse code';
        }
        array_splice($values, 0, 0, array($sdw));
      }
    }
  }
  return $d;
}

function ecbSdwCodesRow($index, $row, $c) {
  if (!isset($c['data']['sdwCodes'])) {
    return $row;
  }
  if ($index === NULL) {
    array_unshift($row, 'Statistical Data Warehouse code');
    return $row;
  }
  $keys = $c['data']['keys'];
  $index = array_map(function ($i, $k) use ($keys) { return $keys[$i][$k]; }, array_keys($index), $index);
  $codes = $c['data']['sdwCodes'];
  while (is_array($codes)) {
    $i = array_shift($index);
    if (isset($codes[$i])) {
      $codes = $codes[$i];
    } else {
      $codes = '';
    }
  }
  array_unshift($row, $codes);
  return $row;
}
