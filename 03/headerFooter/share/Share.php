<?php

class Share {
  private $lang, $project, $staticURL, $language;

  function __construct($staticURL, $themeURL, $lang, $config, $language) {
    $this->staticURL = $staticURL;
    $this->lang = $lang;
    $this->language = $language;
    $this->themeURL = preg_match('/^[a-z]+$/', $themeURL) ? $themeURL : 'default';
    if (isset($config['project'])) {
      $prj = $config['project'];
      $this->project = $prj['url'];
    } else {
      $this->project = '';
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
