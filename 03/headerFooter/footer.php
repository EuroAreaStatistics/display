<?php

require_once 'footer/Footer.php';

(new Footer(
  $themeURL,
  $staticURL,
  $lang,
  $language
))->render();
