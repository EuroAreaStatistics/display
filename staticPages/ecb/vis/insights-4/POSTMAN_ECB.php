<?php
$urls = [
  'data1' => 'BSI/M.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.N.A.A20T.A.I.U2.2240.Z01.A?startPeriod=2004',
  'data2' => 'RAI/Q.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.LTD.Z01.BSI.Z?startPeriod=2004',
];
if (isset($urls[$_GET['code']])) {
  header('Content-Type: application/json');
  $opts = [
    'http' => [
      'method' => 'GET',
      'header' => 'Accept: application/vnd.sdmx.data+json;version=1.0.0-wd',
    ],
    'ssl' => [
      'cafile' => __DIR__ . '/../../../vendors/curl-ca-bundle/src/ca-bundle.crt',
    ],
  ];
  $context = stream_context_create($opts);
  readfile('https://sdw-wsrest.ecb.europa.eu/service/data/' . $urls[$_GET['code']], false, $context);
}
