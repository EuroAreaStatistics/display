<script>
$(function () {
 $('button.definitions').click(function () {
   $(this).nextAll('.definitionsWrapper').toggle();
 });
});
</script>

<style>

li.active {
  padding-right: 5px;
}

section.content .box .dropdown.gray {
  width: calc(100% - 80px);

}
@media only screen and (max-width: 1023px) {
  li.active {
    padding-right: 0;
  }

  section.content .box .dropdown.gray {
    width: auto;
  }
}
</style>

<?php for ($i = 0; $i < $NumberOfFourLinesCharts; $i++) :  ?>
<div class="third">
  <select class='TitleDataSelectorPermanent' id='DataSelect<?=$i?>'>
	<?php    	foreach ($ConfigProject as $k => $v) :  ?>
    <option value='<?=$k?>'><?= strip_tags($v['title']) ?></option>
	<?php	endforeach ?>
  </select><!--
  --><button title="<?= $lang['def_button'] ?>" class="definitions"></button><!--
  --><a href="<?= htmlspecialchars($ConfigProject[$Chart[$i]]['download']) ?>" title="<?= $lang['dload_button'] ?>" class="download"></a>
  <div class="chartsHeight" id="container<?= $i ?>">
    <div class="loadingimage">loading...</div>
  </div>
  <div class='definitionsWrapper'><?= $ConfigProject[$Chart[$i]]['definition'] ?></div>
  <div class="full right"><button class="reset" style="display:none">Reset zoom</button></div>
</div>
<?php endfor ?>
