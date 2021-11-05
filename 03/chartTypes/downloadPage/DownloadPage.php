<?php

class DownloadPage {
  private $title, $head, $tab, $note, $vendorsURL;
  private $nbsp = "\xC2\xA0";

  private function formatSize($size) {
    $unit = '';
    if ($size >= 1024) {
      $size /= 1024;
      $unit = 'K';
    }
    if ($size >= 1024) {
      $size /= 1024;
      $unit = 'M';
    }
    if ($size >= 1024) {
       $size /= 1024;
       $unit = 'G';
    }
    return sprintf('%.2f%s%sB', $size, $this->nbsp, $unit);
  }

  private function formatDate($date) {
    return $date->format('Y-m-d');
  }

  function __construct($config, $vendorsURL) {
    $this->vendorsURL = $vendorsURL;
    $project = $config['project']['url'];
    $this->title = $config['project']['title']['en'];
    if (isset($config['project']['options']['note'])) {
      $this->note = $config['project']['options']['note']['en'];
    }
    $this->tab = [];
    foreach ($config['project']['tabs'] as $i => $tabId) {
      $tab = $config['tabs'][$tabId];
      $charts = [];
      foreach ($tab['charts'] as $j => $chartId) {
        $chart = $config['charts'][$chartId];
        if (!isset($chart['data'])) continue;
        if ($chart['data']['keys'][1][0] === '') {
          // set options for banks corner
          foreach ($chart['data']['keys'][0] as $key) {
            $chart['data']['csvUrl'] = '//sdw-wsrest.ecb.europa.eu/service/data/'.$key;
          }
        }
        $tmp = [
          'name' => $chart['title']['en'],
          'description' => $chart['definition']['en'],
          'download' => [],
        ];
        if (isset($chart['data']['csvUrl'])) {
          foreach (['XML' => 'application/vnd.sdmx.genericdata+xml;version=2.1', 'CSV' => 'text/csv'] as $format => $mime) {
            $url = $chart['data']['csvUrl'];
            $parts = explode('/', $url);
            $parts = array_reverse($parts);
            if ($parts[2] == 'data') {
              $tmp['flow'] = $parts[1];
            } else {
              $tmp['flow'] = $parts[0];
            }
            $tmp['download'][] = ['format' => $format, url => $url, 'mime' => $mime];
          }
        } else {
          if (isset($chart['data']['fetchDate'])) {
            $tmp['date'] = $this->formatDate(new DateTime($chart['data']['fetchDate']));
          }
          if (isset($chart['data']['url'])) {
            $tmp['download'][] = ['size' => $this->_('unknown'), 'format' => 'SDW', url => $chart['data']['url']];
          }
          $csv = fopen('php://temp', 'w+');
          fputcsv($csv, array_merge([''], $chart['data']['keys'][1]));
          foreach ($chart['data']['data'] as $i => $line) fputcsv($csv, array_merge([$chart['data']['keys'][0][$i]], $line));
          $size = ftell($csv);
          $tmp['download'][] = ['size' => $this->formatSize($size), 'format' => $this->_('CSV'), url => 'data:text/csv;base64,'.base64_encode(stream_get_contents($csv, -1, 0))];
          fclose($csv);
        }
        $charts[] = $tmp;
      }
      $this->tab[] = [
        'name' => $tab['title']['en'],
        'description' => $tab['teaser']['en'],
        'chart' => $charts,
      ];
    }
  }

  private function _($t) {
    return $t;
  }

  function render() {
    require 'template.php';
  }
}
