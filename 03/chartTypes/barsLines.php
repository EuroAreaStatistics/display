
<?php

$NumberOfBarsLinesCharts = count($Chart);
$tabID = $config['project']['tabs'][$page];


?>

	<div class="main-container main">
	    <div class="wrapper clearfix">

		<div id='teaserText'><?= $TeaserText ?></div>

			<div id="chartareaColumns">

			    <div id="teaserStory" class="teaserStory"><?= $config['tabs'][$tabID]['teaser'][$language] ?></div>
		
			    <div id="titleDisplaySelectInstruction" class="teaserStory"></div>

          <div id="CompareItem">

              <?= $lang['Compare'] ?> >

              <select id="BreadcrumbCountry" style="color: #0072c4" <!--ONCHANGE="location = this.options[this.selectedIndex].value;"-->>
                <option value=""><?= $lang['selectCountry'] ?></option>

<?php foreach($lang_countries as $k => $v):
                if (!in_array($k,$allCountries)) continue;
?>
                <option name="<?= $k ?>" value="<?= $k ?>"><?= $v ?></option>

<?php endforeach; ?>

              </select>


            <span><?= $lang['with'] ?></span>

            <select id="countrySelect1" style="color: #ff0000">
              <option value=""><?= $lang['addCountry'] ?></option>
<?php foreach($lang_countries as $k => $v):
                if (!in_array($k,$allCountries)) continue;
?>
              <option name="<?= $k ?>" value="<?= $k ?>"><?= $v ?></option>

<?php endforeach; ?>
            </select>

          </div>

<?php	for ($i = 0; $i < $NumberOfBarsLinesCharts; $i++) : ?>
			    <div class='chartsColumns'>
					<h3 title='<?=$ConfigProject[$Chart[$i]]['definition']?>' class='chartMainTitle'><?=$ConfigProject[$Chart[$i]]['title']?></h3>
					<h4 class='chartTitle1'><?=$ConfigProject[$Chart[$i]]['subTitle1']?></h4>
					<h4 class='chartTitle2'><?=$ConfigProject[$Chart[$i]]['subTitle2']?></h4>
					<div class='chart201' id='containerColumns<?=$i?>'></div>
					<div class='chart202' id='container<?=$i?>'></div>
			    </div>
<?php	endfor ?>
    
			</div>
        	  		    
	    </div> <!--#main--> 
	</div> <!-- #main-container -->
