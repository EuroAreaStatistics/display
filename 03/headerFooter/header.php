<?php

require_once 'header/Header.php';

(new Header(
  $staticURL,
  $themeURL,
  $lang,
  $language,
  $shareLanguages,
  isset($config) ? $config['languages'] : [],
  $templateList,
  $config,
  $page,
  $embed ?? FALSE
))->render();
