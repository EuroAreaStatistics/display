<?php


$tabID = $config['project']['tabs'][$page];

if ($chartType == 'mapWizardStandard') {
  require __DIR__.'/chartTypes/mapChartWizard/getSubTabs.php';
  $subTabs = getSubTabs($config);
} else {
  $subTabs = array();
}

?>


<style>

  #headerImage {
    width: 100%;
  }

  #itemContainer {
    height: auto;
    display: inline;
  }

  #topContainer {
      position: relative;
      max-width: none;
      width: 100%;
      right: 0;
      left: 0;
      display: inline;
      padding: 0;
      margin: 0;
      margin-top: 20px;
      background-color: #ffffff;
  }

</style>


<img id="headerImage" src="<?= $staticURL ?>/img/pdfHeader.png""  alt="header">

<div id='topContainer'>
  <div id="itemContainer">
      <h3>
          <span id="teaserTitle" class="noteCountryName"></span>
          <span class="teaserTitle2"> - <?= $lang['titleCountrySnapshot'] ?></span>
      </h3>
      <nav id="panelNav"></nav>
  </div>
</div>

<script>
  var subTabs = <?= json_encode($subTabs) ?>;
  updateCountryProfile(urlcountry,'pdf');
</script>


