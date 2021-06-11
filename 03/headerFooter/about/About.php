<?php

class About {
  private $lang, $dataLinks, $coverLink, $publicationName, $relatedWebsite, $bankLinks;

  private function getOption($prj, $option, $language) {
    if (isset($prj['options'][$option][$language])) {
      return $prj['options'][$option][$language];
    } else if (isset($prj['options'][$option])) {
      return $prj['options'][$option];
    } else {
      return NULL;
    }
  }

  private function getImageLink($prj, $option, $language) {
    if (isset($prj['options'][$option][$language])) {
      $value = $prj['options'][$option][$language];
    } else if (isset($prj['options'][$option])) {
      $value =  $prj['options'][$option];
    } else {
      $value =  NULL;
    }
    $value = str_replace('http://','https://',$value);
    return $value;
  }

  function __construct($themeURL, $lang, $config, $bankLinks, $language) {
    $this->lang = $lang;
    $this->bankLinks = $bankLinks;
    $this->themeURL = preg_match('/^[a-z]+$/', $themeURL) ? $themeURL : 'default';
    $this->dataLinks = array();
    if (isset($config['project'])) {
      $prj = $config['project'];
      $this->publicationName = $this->getOption($prj, 'relatedPublication', $language);
      $this->coverLink = $this->getImageLink($prj, 'publicationThumbnail', $language);
      $this->relatedWebsite = $this->getOption($prj, 'publicationWebpage', $language);
      $dataLink = $this->getOption($prj, 'dataSourceURL', $language);
      if (isset($dataLink)) {
        $this->dataLinks[$dataLink] = $this->getOption($prj, 'dataSource', $language);
      }
    } else {
      $this->publicationName = '';
      $this->coverLink = '';
      $this->relatedWebsite = '';
    }
  }

  function render() {
    $template = __DIR__."/template-$this->themeURL.php";
    if (!file_exists($template)) {
      $template = __DIR__."/template-default.php";
    }
    require $template;
  }
}
