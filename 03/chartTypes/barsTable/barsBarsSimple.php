
<style>

.chart {
    min-width: 180px;
    width: auto;
}

.subTitle {
	font-weight: normal;
}

#central-container {
    margin-top: 40px;
}

.main {
	min-height: 0px;
}

.wrapper {
    margin-bottom: 10px;
}


.countryList {
	min-width: 70px;
	max-width: 160px;
	text-align: right;
}

.countryShort {
	display: none;
}

.countryLong {
	display: inline;
}

.simpleDataSource {
	position: fixed;
	bottom: 0px;
	right: 0px;
	font-size: 10px;
	width: 100%;
	background-color: white;
	text-align: right;
	padding-right: 6px;
	padding-bottom: 2px;
}

</style>

	<div class="main-container main">
	    <div class="wrapper clearfix">
		<div class="container_wrapper">
		    <div class="container">

				<div id='resultsTable'>

			    <table id='results' class="tablesorter">
				<thead>
			            <tr>
					<th  class='dataHeading'><?= $lang['ranking']['country'] ?></th>
<?php foreach ($Chart as $v) : ?>
					<th  class="dataHeading">
					    <div title="<?= $data[$v]['definition'] ?>">Indicator</div>
					</th>
<?php endforeach; unset($v); ?>
				    </tr>
				</thead>
				<tbody>
<?php 	foreach ($countryNames as $country): ?>
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
	<?php foreach ($Chart as $v) : ?>
						<td  class='chart' title='' >
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
		    </div>
		</div>
	    </div> <!--#main-->

		<div class='simpleDataSource'>
			<?= $lang['aboutDataSource'] ?>:
<?php if ($config['project']['options']['dataSourceURL'][$language]) : ?>
      <a href='<?= $config['project']['options']['dataSourceURL'][$language] ?>' target='_blank'><?= $config['project']['options']['dataSource'][$language] ?></a>
<?php else : ?>
      <?= $config['project']['options']['dataSource'][$language] ?>
<?php endif ?>
		</div>

	</div> <!-- #main-container -->

