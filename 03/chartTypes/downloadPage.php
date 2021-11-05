<?php

require_once 'downloadPage/DownloadPage.php';

(new DownloadPage($config, $vendorsURL))->render();
