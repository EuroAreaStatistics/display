<?php

require_once 'about/About.php';

(new About(
  $themeURL,
  $lang,
  $config,
  isset($bankLinks) ? $bankLinks : array(),
  $language
))->render();
