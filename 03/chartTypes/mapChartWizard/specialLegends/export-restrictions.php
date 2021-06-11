
<style>

.map-legend .scale ul li {
  float: none;
  width: auto;
}


.legend-scale-wrapper {
  float: none;
  left: 0;
  width: auto;
}

.legend-scale-wrapper table {
  width: 100%;
  margin-top: -10px;
}


.legend-scale-wrapper table td {
  height: 18px;
  max-height: 18px;
  font-size: 80%;
  padding: 0;
  overflow: hidden;
  cursor: default;
}

.map-legend table td span {
    padding-right: 12px;
    margin-right: 2px;
}

.legend-scale-wrapper table {
  width: 100%;
}

#item1Container .leftLegend {
  width: 40%;
}

#item1Container .rightLegend {
  width: 60%;
}

#item2Container .tab {
  padding-left: 30px;
  margin-top: 45px;
}

#item2Container .tab .textElement p {
  font-size: 14px;
}

#topContainer {
  opacity: 0.95;
}


</style>

<?php

$legendTitle['en'] = 'Most restrictive trade measure';
$legendTitle['cn'] = '最具限制性的贸易措施';

$legendText['en'] = 'Click on countries to access<br>a detailed country profile.';
$legendText['cn'] = '点击国家进入详细国家剖析';

$legendExplore['en'] = 'start exploring';
$legendExplore['cn'] = '开始探索';

if ($page == 0) {
  $defaultColor = '#bababa';
} else {
  $defaultColor = '#464646';
}

$legendLang['en'] = array (
  0=>array (
    'export prohibition',
    'an absolute restriction on exports'
  ),
  1=>array (
    'export quota',
    'a prescribed maximum volume of exports'
  ),
  2=>array (
    'export tax',
    'a tax collected on goods leaving a customs territory. Export taxes are generally set either on a per unit basis or an ad valorem basis. Export tax, fiscal tax on exports and export surtax included'
  ),
  3=>array (
    'non-automatic licensing',
    'the requirement to obtain prior approval, in the form of a license or permit, to export a commodity. This practice requires submission of an application or other documentation as a condition for authorisation to export'
  ),
  4=>array (
    'other measures',
    'refers to export restrictions other than a ban, tax, quota or license. This includes export surtaxes, minimum export prices, VAT tax rebate reductions or withdrawals, dual pricing schemes, restrictions on customs clearance points, qualified exporters lists, domestic market obligations and captive mining'
  ),
  5=>array (
    'no restrictions',
    'no export restriction was applied'
  ),
  6=>array (
    'not researched',
  ),
);


$legendLang['cn'] = array (
  0=>array (
    '禁止出口',
    '绝对限制进口'
  ),
  1=>array (
    '出口配额',
    '规定最高出口限额'
  ),
  2=>array (
    '出口税',
    '对离开某关税区的货物征收的税，通常按单位或从价规定出口税，含出口税、对出口财政税和出口附加税'
  ),
  3=>array (
    '非自动许可',
    '出口商品需以执照或许可证形式事先获得批准，需提交申请或其他证明文件作为授权出口的条件'
  ),
  4=>array (
    '其他措施',
    '指的是禁令、税收、配额或许可以外的出口限制，这包括出口附加税、最低出口价、增值税退税、减税或免税、双重定价方案、清关点限制、符合资格的出口商清单、国内市场义务和专属采掘'
  ),
  5=>array (
    '无限制',
    '未适用出口限制'
  ),
  6=>array (
    '没有研究',
  ),
);

foreach ($legendLang[$language] as $code => $items) {
  $legendLang[$language][$code][2] = $items[0].' = '.$items[1];
}

?>


  <div id='topContainer'>
<?php if ($config['tabs'][$tabID]['teaserLayer'] == true): ?>
    <div id="item2Container" class="containerContent">
      <div class="closeLayer sharebutton closeButton">x</div>
      <div class="tab active">
        <div class='textElement'>
          <p><?= $config['tabs'][$tabID]['teaser'][$language] ?></p>
        </div>
        <div id="helptextStart">
          <span id="nextStory" class="closeLayer storybutton roundBorders"><?= $legendExplore[$language] ?></span>
        </div>
      </div>
    </div>
<?php endif ?>
<?php if ($chartType == 'mapWizardStandard'): ?>
    <div id="itemContainer"  class="containerContent">
        <div class="closeLayer sharebutton closeButton">x</div>
        <h3>
            <span id="teaserTitle" class="noteCountryName"></span>
            <span class="teaserTitle2"> - <?= $lang['titleCountrySnapshot'] ?></span>
        </h3>
        <nav id="panelNav"></nav>
    </div>
<?php endif ?>
    <div id="item1Container"  class="containerContent">
      <div class='map-legend'>
        <div id='textLegend' class='legendItem leftLegend'>
          <div class='legendText'><?= $legendText[$language] ?></div>
        </div>
        <div id='sizeLegend' class='legendItem leftLegend'>
          <div class='legendText'><?= $lang['mapBubbleSize']?>: <span id='legendTitleSize'></span><span class='subTitle' id='legendUnitSize'></span>, <span class='subTitle' id='legendYearSize'></span></div>
          <div  id='legendBubble' class='legendText'></div>
        </div>
        <div id='colorLegend' class='legendItem rightLegend'>
<!--          <div class='legendText'>
            <span id='legendTitleColor'></span><span class='subTitle' id='legendUnitColor'></span>, <span class='subTitle' id='legendYearColor'></span>
          </div>
-->
          <div class="legend-scale-wrapper">
            <table>
              <tr>
                <td colspan="2"><?= $legendTitle[$language] ?></td>
                <td title='<?=$legendLang[$language][0][2]?>'><span style='background:<?= $MapColors[0]?>;'></span>= <?=$legendLang[$language][0][0] ?></td>
              </tr>
              <tr>
                <td title='<?=$legendLang[$language][1][2]?>'><span style='background:<?= $MapColors[1]?>;'></span>= <?=$legendLang[$language][1][0] ?></td>
                <td title='<?=$legendLang[$language][2][2]?>'><span style='background:<?= $MapColors[2]?>;'></span>= <?=$legendLang[$language][2][0] ?></td>
                <td title='<?=$legendLang[$language][3][2]?>'><span style='background:<?= $MapColors[3]?>;'></span>= <?=$legendLang[$language][3][0] ?></td>
              </tr>
              <tr>
                <td title='<?=$legendLang[$language][4][2]?>'><span style='background:<?= $MapColors[4]?>;'></span>= <?=$legendLang[$language][4][0] ?></td>
                <td title='<?=$legendLang[$language][5][2]?>'><span style='background:<?= $MapColors[5]?>;'></span>= <?=$legendLang[$language][5][0] ?></td>
                <td><span style='background:<?= $defaultColor?>;'></span>= <?=$legendLang[$language][6][0] ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
