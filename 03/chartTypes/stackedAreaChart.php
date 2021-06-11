<?php

$tabID = $config['project']['tabs'][$page];
$charts = $config['tabs'][$tabID]['charts'];

$stackType = $config['tabs'][$tabID]['stackType'];


?>

<style>

.chartArea {
  width: 50%;
  height: 330px;
}

.simpleChartHeight {
  height: 280px;
  width: 100%;
  float: left;
  margin-right: 0px;
}

#chartSelector {
  width: 100%;
  margin-top: 15px;
  margin-bottom: 15px;
}

.main select {
  margin-left: 15px;
}

.main {
  margin-bottom: 50px;
}

@media only screen and (min-width: 580px) {

  .chartArea {
    float: left;
    width: 50%;
    height: 350px;
  }

  .simpleChartHeight {
    height: 350px;
  }
}

<?php if ($stackType == 'pyramids') : ?>

  .simpleChartHeight {
    width: 50%;
  }

<?php endif ?>

</style>
  
<div class="main-container main">
  <div class="wrapper clearfix">
    <div id="teaserStory" class="teaserStory"><?= $config['tabs'][$tabID]['teaser'][$language]  ?></div>

    <div id=chartSelector>
      <select id='chartTitle'>
<?php foreach ($config['charts'] as $k => $v) :?>
<?php if (!in_array($k,$charts)) {continue;} ?>
        <option value='<?= $k ?>'><?= $v['title'][$language] ?></option>
<?php endforeach ?>
      </select>
    </div>

    <div class='chartArea'>
      <select id='countrySelect'></select>
      <div>
<?php if ($stackType == 'pyramids') : ?>
        <div class="simpleChartHeight" id="containerA"></div>
<?php endif ?>
        <div class="simpleChartHeight" id="container"></div>
      </div>
    </div>

    <div class='chartArea'>
      <select id='countrySelect1'></select>
      <div>
<?php if ($stackType == 'pyramids') : ?>
        <div class="simpleChartHeight" id="container1A"></div>
<?php endif ?>
        <div class="simpleChartHeight" id="container1"></div>
      </div>
<?php if ($stackType == 'pyramids') : ?>
      <p>
        <label for="year">Year:</label>
        <input type="text" id="year" readonly style="border:0; font-weight:bold;">
        <div id="slider"></div>
      </p>
<?php endif ?>
    </div>
  </div>

</div> <!-- #main-container -->



