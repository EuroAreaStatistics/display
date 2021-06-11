

	<table id=chartTable>
		<tr>
<?php    for ($i = 0; $i < $NumberOfColumnCharts; $i++) :  ?>
		    <td class='chartTitle chartColumn charts<?=$NumberOfColumnCharts?>0<?=$i+1?>'>
    <?php if ($titleDisplay == 'select') : ?>
			    <select class='TitleDataSelectorPermanent' id='DataSelect<?=$i?>'>
	<?php    	foreach ($ConfigProject as $k => $v) :  ?>
				<option value='<?=$k?>'><?= strip_tags($v['title']) ?></option>
	<?php	endforeach ?>	    
			    </select>		    
    <?php	else : ?>
			    <h3 class='chartTitle1'><?= $ConfigProject[$Chart[$i]]['title']?></h3>
    <?php	endif ?>

		    </td>
<?php	endfor ?>
		</tr>
		<tr>
<?php    for ($i = 0; $i < $NumberOfColumnCharts; $i++) :  ?>		
		    <td class="chartColumn charts<?=$NumberOfColumnCharts?>0<?=$i+1?>">
			<div class="chartsHeight" id="containerColumns<?= $i ?>">
			    <div class="loadingimage">loading...</div>
			</div>
    <?php      if (isset($ConfigProject[$Chart[$i]]['download'])): ?>
			<div class='downloadsWrapper'>
			    <div class="downloads"><a href='<?= htmlspecialchars($ConfigProject[$Chart[$i]]['download']) ?>'><?= $lang['download_text'] ?></a></div>
			</div>
    <?php      endif ?>
		    </td>
<?php	endfor ?>
		</tr>
		<tr>
<?php    for ($i = 0; $i < $NumberOfColumnCharts; $i++) :  ?>
		    <td class="chartColumn charts<?=$NumberOfColumnCharts?>0<?=$i+1?>">
			<div class='definitionsWrapper'>    
			    <div class="definitions"><?= $ConfigProject[$Chart[$i]]['definition'] ?></div>
			</div>
		    </td>
<?php    endfor ?>
		</tr>
	</table>
