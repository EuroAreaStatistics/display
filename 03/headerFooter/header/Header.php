<?php

class Header {
  private $home, $staticURL, $lang, $languages, $templates, $tabs, $themeURL, $language, $project;

  function __construct($staticURL, $themeURL, $lang, $language, $shareLanguages, $shareLanguagesProject, $templateList, $config, $page, $embed = FALSE) {
    $this->staticURL = $staticURL;
    $this->lang = $lang;
    $this->language = $language;
    $this->themeURL = preg_match('/^[a-z]+$/', $themeURL) ? $themeURL : 'default';
    $this->home = '/?'.http_build_query(array('lg' => $language));
    $this->languages = array();
    if (isset($config['project'])) {
      $prj = $config['project'];
      $project = $prj['url'];
      $this->project = $prj['url'];
      $this->pageTitle = $prj['title'][$language];
    } else {
      $project = '';
      $this->project = '';
      $this->pageTitle = '';
    }
    if ($themeURL == 'ecb') {
       $this->languages = $shareLanguages;
    } elseif ($themeURL == 'oecd') {
      $this->languages['en'] = $shareLanguages['en'];
      if (isset($shareLanguagesProject)) {
        foreach ($shareLanguagesProject as $v) {
          $this->languages[$v] = $shareLanguages[$v];
        }
      }
    } else {
      if (count($shareLanguagesProject) > 1) {
        foreach ($shareLanguagesProject as $v) {
          $this->languages[$v] = $shareLanguages[$v];
        }
      }
    }
    $this->templates = array();
    $this->tabs = array();
    $this->embed = $embed;
    if (isset($prj['tabs'])) {
      $tabID = $prj['tabs'][$page];
      if (isset($config['tabs'][$tabID]['altTemplate'])) {
        $altTemplate = $config['tabs'][$tabID]['altTemplate'];
        if ($themeURL == 'oecd' && !isset($config['project']['options']['noDataDownload'])) {
// add download page for oecd templates
          array_push ($altTemplate, 10);
        }
      } else {
        $altTemplate = array();
        if ($themeURL == 'oecd' && !isset($config['project']['options']['noDataDownload'])) {
// add download page for oecd templates
          array_push ($altTemplate, 10);
        }
      }
      if ($themeURL == 'ecb' || $themeURL == 'oecd') {
        foreach ($altTemplate as $v) {
          $this->templates[$v] = !empty($lang['template'][$v]) ? $lang['template'][$v] : $templateList[$v]['displayName'];
        }
      } else {
        foreach ($templateList as $k => $v) {
          if (in_array($k, $altTemplate)) {
            $this->templates[$k] = $v['name'];
          }
        }
      }
      foreach ($prj['tabs'] as $tabID) {
        $tab = $config['tabs'][$tabID];
        $this->tabs[] = strip_tags($tab['title'][$language]);
      }
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
