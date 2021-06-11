<ul class="tabs">
<?php  foreach ($config['project']['tabs'] as $i => $t): ?>
            <li data-tab="<?= htmlspecialchars($i) ?>"><?= strip_tags($config['tabs'][$t]['title'][$language]) ?></li>
<?php  endforeach ?>
</ul>
<ul class="tabcontent">
  <li class="active">
<?php

$tabID = $config['project']['tabs'][$page];

if (isset($config['tabs'][$tabID]['titleDisplay'])&&$config['tabs'][$tabID]['titleDisplay'] == true) {
  $titleDisplay = 'select';
}

?>
<?php if ($titleDisplay == 'select') :?>
    <p><?= $lang['titleDisplaySelectInstruction'] ?></p>
<?php endif ?>
<?php
  if ($chartDisplay == 'lines') {
    $NumberOfFourLinesCharts = count($Chart);
    include 'standardChart/ChartAreaLines.php';
  } elseif ($chartDisplay == 'columns') {
    $NumberOfColumnCharts = count($Chart);
    include 'standardChart/ChartAreaColumns.php';
  }
  include 'standardChart/MenuAndButtonpanel.php';
?>
  </li>
</ul>
