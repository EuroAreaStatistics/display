
<div class="main-container main">
  <div class="wrapper clearfix">
    <div class="container_wrapper">
      <div class="container">
<?php if (!$headerFooterLayout) : ?>
    <?php    if ($chartDisplay == 'BarsDiamondsIndicators') : ?>
      <div class='teaserStory'><?= $config['tabs'][$tabID]['teaser'][$language] ?></div>
    <?php else : ?>
      <div class='teaserStory'><?= $config['tabs'][$tabID]['teaser'][$language] ?></div>
      <div class='chartTitle'><?= $data['title'] ?></div>
      <div class='chartSubTitle'><?= $data['definition'] ?></div>
    <?php endif ?>
<?php endif ?>
      <div class='helpText'><?= $lang['ranking']['helpText'] ?></div>

      <div id='resultsTable'>
          <table id='results' class="tablesorter">
          <thead>
            <tr>
              <th  class='dataHeading'><?= $lang['ranking']['country'] ?></th>
              <th  class='dataHeading'></th>
              <th  class='dataHeading' title="<?= htmlspecialchars($data['circleSeriesDef']) ?>" >
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="14px" height="15px">
                    <circle cy="9" cx="7" r="6" stroke="grey" stroke-width="0" fill="#04619a" />
                  </svg>
                  = <?= $data['circleSeries'] ?><span class='headingUnit'><?=$data['circleSeriesUnit'] ?></span>
                </div>
              </th>
              <th  class='dataHeading' title="<?= htmlspecialchars($data['barsSeriesDef']) ?>">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="16px" width="16px">
                    <rect y="5" x="1" height="10" width="15" stroke="#8fa4b1" stroke-width="2" fill="#8fa4b1" />
                  </svg>
                  = <?= $data['barsSeries'] ?><span class='headingUnit'><?=$data['barsSeriesUnit'] ?></span>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
  <?php   foreach ($data['barsValue'] as $country => $value): ?>
            <tr>
            <td class='countryList'>
    <?php if (array_key_exists($country,$ISO3toFlags)) : ?>
              <span class='countryLong'><?= $lang_countries[strtolower($country)] ?></span>
              <span class='countryShort'><?= $lang_countries_ISO[strtolower($country)] ?></span>
              <img class='countryFlag' src='<?= $baseURL ?>/flags.png?cr=<?= $country ?>'>
    <?php else : ?>
              <span class='countryLong'><?= $country ?></span>
              <span class='countryShort'><?= $country ?></span>
    <?php endif ?>
            </td>
              <td  class='chart' title='' >
                <span class='bar'>
                  <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="15px">
                    <rect y="3.5" x="1" height="10" width="<?= $data['barsWidth'][$country] ?>" stroke="#8fa4b1" stroke-width="2" fill="#8fa4b1" />
                  </svg>
                </span>
    <?php if ($data['circlePos'][$country] != null) : ?>
                <span class='circle' style='margin-left:<?= $data['circlePos'][$country]-4.8 ?>px'>
                  <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="15px" height="15px">
                    <circle cy="9" cx="7" r="4.8" stroke="#ffffff" stroke-width="0.5" fill="#04619a" />
                  </svg>
                </span>
    <?php endif ?>
              </td>
    <?php if ($data['circleValue'][$country] != null) : ?>
                <td  class='dataResults'><?= $data['circleValue'][$country]?></td>
    <?php else :?>
                <td  class='dataResults'>-</td>
    <?php endif?>
    <?php if ($data['barsValue'][$country] != null) : ?>
              <td  class='dataResults'><?= $data['barsValue'][$country]?></td>
    <?php else :?>
                <td  class='dataResults'>-</td>
    <?php endif?>
            </tr>
  <?php endforeach; ?>
          </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> <!--#main-->
</div> <!-- #main-container -->
