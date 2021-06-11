<?php

require_once 'share/Share.php';

(new Share(
  $staticURL,
  $themeURL,
  $lang,
  $config,
  $language
))->render();
