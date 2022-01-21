
  <div id='topContainer'>
<?php if ($chartType == 'mapWizardStandard'): ?>
    <div id="itemContainer">
        <div class="closeLayer sharebutton closeButton">x</div>
        <h3>
            <span id="teaserTitle" class="noteCountryName"></span>
            <span class="teaserTitle2"> - <?= $lang['titleCountrySnapshot'] ?></span>
        </h3>
        <nav id="panelNav"></nav>
    </div>
<?php endif ?>
    <div id="item1Container">
      <div class='map-legend'>
        <div id='textLegend' class='legendItem'>
          <div class='legendText'>Click on countries to access<br>a detailed country profile.</div>
        </div>
        <div id='sizeLegend' class='legendItem'>
          <div class='legendText'><?= $lang['mapBubbleSize'] ?>: <span id='legendTitleSize'></span><span class='subTitle' id='legendUnitSize'></span>, <span class='subTitle' id='legendYearSize'></span></div>
          <div  id='legendBubble' class='legendText'></div>
        </div>
        <div id='colorLegend' class='legendItem'>
          <div class='legendText'>
            <span id='legendTitleColor'></span><span class='subTitle' id='legendUnitColor'></span>, <span class='subTitle' id='legendYearColor'></span>
          </div>
          <div class="legend-scale-wrapper">
            <div class='scale'>
              <ul>
<?php foreach ($MapColors as $i => $c): ?>
<?php   if ($i == 0): ?>
                <li class='firstLegendElement'><span id='legendMinColor' class='firstLegendElement' style='background:<?= $c ?>;'>min Value</span></li>
<?php   elseif ($i < count($MapColors)-1): ?>
                <li><span style='background:<?= $c ?>;'></span></li>
<?php   else: ?>
                <li class='lastLegendElement'><span id='legendMaxColor' class='lastLegendElement' style='background:<?= $c ?>;'>max Value</span></li>
<?php   endif ?>
<?php endforeach; unset($i, $c) ?>
              </ul>
            </div>
          </div>
          <div class="sliderWrapper" style="clear:both;padding-bottom:3px;padding-top:5px">
            <div class="sliderContainer">
              <div name="sliderMapColor" style="width:calc(100% - 1.8em - 13px);margin-right:10px;display:inline-block;vertical-align:middle"></div>
              <input type="button" style="font-size:0.9em;height:1.8em;width:1.8em;padding:0"/>
            </div>
          </div>
          <div class='legendText' id='legendSubtextColor'></div>
<?php if (isset($lang['mapSource'])): ?>
          <div class='legendText' style='clear:both'><?= $lang['mapSource'] ?></div>
<?php endif ?>
        </div>
      </div>
    </div>
  </div>
