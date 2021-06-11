

<style>

.chart {
  min-width: 180px;
  width: auto;
}

.subTitle {
  font-weight: normal;
}

@media only screen and (max-width: 1024px) {
  .column2 {display: none;}
}

@media only screen and (max-width: 674px) {
  .column1 {display: none;}
}


</style>

<ul class="tabs">
<?php  foreach ($config['project']['tabs'] as $i => $t): ?>
            <li data-tab="<?= htmlspecialchars($i) ?>"><?= strip_tags($config['tabs'][$t]['title'][$language]) ?></li>
<?php  endforeach ?>
</ul>
<ul class="tabcontent">
  <li class="active">
            <p><?= $lang['ranking']['helpText'] ?></p>
            <div id='resultsTable'>
              <table id='results' class="tablesorter">
                <thead>
                  <tr>
                    <td class='dataHeading'></td>
<?php if (!empty($data['select'])) : ?>
  <?php foreach ($Chart as $k => $v) : ?>
                    <td class='dataHeading column<?= $k ?>' >
                      <select style="font-size: 10px" id="DataSelect<?= $k ?>" >
    <?php foreach ($data['select'] as $w => $title) : ?>
                        <option value="<?= $w ?>" <?= $v == $w ? 'selected' : '' ?>><?= strip_tags($title) ?></option>
    <?php endforeach; unset($w); unset($title); ?>
                      </select>
                    </td>
  <?php endforeach; unset($k, $v); ?>
<?php endif ?>
                </tr>
                <tr>
                    <th  class='dataHeading'><?= $lang['ranking']['country'] ?></th>
<?php foreach ($Chart as $k => $v) : ?>
                    <th  class="dataHeading  column<?=$k?>">
                      <div title="<?= htmlspecialchars(strip_tags($data[$v]['definition'])) ?>">
<?php if ($data[$v]['unit'] != null) :?>
                        <span class='subTitle'><?=$data[$v]['unit']?>,</span>
<?php endif ?>
                        <span class='subTitle'><?=$data[$v]['yearMax'] ?></span>
                      </div>
                    </th>
<?php endforeach; unset($k, $v); ?>
                  </tr>
                </thead>
                <tbody>
<?php     foreach ($countryNames as $country): ?>
                  <tr>
                    <td class='countryList'>
  <?php if (array_key_exists(strtoupper($country),$ISO3toFlags)) : ?>
                      <span class='countryLong'><?= $lang_countries_titles[strtolower($country)] ?></span>
                      <span class='countryShort'><?= $lang_countries_ISO[strtolower($country)] ?></span>
                      <img class='countryFlag' src='<?= $baseURL ?>/flags.png?cr=<?= $country ?>'>
  <?php elseif (array_key_exists(strtolower($country),$lang_countries_titles)) : ?>
                      <span class='countryLong'><?= $lang_countries_titles[strtolower($country)] ?></span>
                      <span class='countryShort'><?= $lang_countries_ISO[strtolower($country)] ?></span>
  <?php else : ?>
                      <span class='countryLong'><?= $country ?></span>
                      <span class='countryShort'><?= $country ?></span>
  <?php endif ?>
                    </td>
  <?php foreach ($Chart as $k => $v) : ?>
  <?php if (isset($data[$v]['tooltip'][$country])) : ?>
                    <td  class='chart column<?=$k?>' title="<?= htmlspecialchars($data[$v]['tooltip'][$country]) ?>" >
  <?php else : ?>
                    <td  class='chart column<?=$k?>'>
  <?php endif ?>
                      <span class='bars'>
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="15px" width="<?= $data[$v]['labelLength'] ?>px">
                          <g transform="translate(0,0)">
                            <rect y="5" x="<?= $data[$v]['position'][$country] ?>" height="10" width="<?= $data[$v]['width'][$country] ?>" stroke="#8fa4b1" stroke-width="2" fill="#8fa4b1" />
                            <text text-anchor="start" x="<?= $data[$v]['posText'][$country]+4 ?>" y="13"  font-family="Verdana" font-size="11" color="#636363"><?= $data[$v]['label'][$country] ?></text>
                          </g>
                        </svg>
                      </span>
                    </td>
  <?php endforeach ?>
                  </tr>
<?php endforeach; ?>
                </tbody>
              </table>
            </div>
  </li>
</ul>
