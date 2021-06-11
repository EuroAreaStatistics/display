<?php

class Footer {
  private $themeURL, $lang, $staticURL, $home;

  function __construct($themeURL, $staticURL, $lang, $language) {
    $this->staticURL = $staticURL;
    $this->lang = $lang;
    $this->themeURL = preg_match('/^[a-z]+$/', $themeURL) ? $themeURL : 'default';
    $this->home = '/?'.http_build_query(array('lg' => $language));
  }

  function render() {
    $template = __DIR__."/template-$this->themeURL.php";
    if (!file_exists($template)) {
      $template = __DIR__."/template-default.php";
    }
    require $template;
  }
}
