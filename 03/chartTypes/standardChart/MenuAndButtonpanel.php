<?php require __DIR__.'/CounterpartAreas.php'; ?>
<?php

function verticaleTable($items, $columns) {
  $table = '';
  $remainder = count($items) % $columns;
  $fullRows = (count($items) - $remainder) / $columns;
  $rows = $fullRows + ($remainder ? 1 : 0);
  for ($i = 0; $i < $rows ; $i++) {
    $table .= "<tr>";
    for ($j = 0; $j < $columns ; $j++) {
      if ($i === $fullRows && $j === $remainder) {
        break;
      }
      $table .= "<td>";
      $table .= $items[$i + $j*$fullRows + min($j, $remainder)];
      $table .= "</td>";
    }
    $table .= "</tr>";
  }
  return $table;
}

?>
<style>

.ui-tooltip {
  max-width: 1200px;
}

.multi-column {
  font-size: 75%;
}

table.multi-column  {
  width: 100%;
}

.multi-column td  {
  padding-left: 1em;
}

.multi-column td:nth-child(1)  {
  padding-left: 0;
}


.ui-tooltip h3 {
  margin-top: 0;
}

.country-buttons {
  display: -ms-flexbox;
  display: flex;
}

.country-buttons img {
  height: 25px;
  margin: auto 8px auto 0;
}

.country-buttons span {
  margin: auto;
}

</style>

<div class="buttons light buttonpanel">
<?php foreach($lang_countries as $k => $v): ?>
  <?php if (isset($counterpartAreas[$k])): ?>
    <button class="country-buttons" code="<?= $k ?>" data-title="<?= $lang_countries_ISO[$k] ?>"><?php if (!empty($features['buttonFlags'])): ?><img src="<?= $baseURL ?>/flags.png?cr=<?= $k ?>"><?php endif ?><span><?= $v ?></span><div style="display:none"><h3><?= $v ?></h3><table class="multi-column">
<?= verticaleTable($counterpartAreas[$k], 3) ?>
    </table></div></button>
  <?php else: ?>
    <button class="country-buttons" code="<?= $k ?>" title="<?= $lang_countries_ISO[$k] ?>"><?php if (!empty($features['buttonFlags'])): ?><img src="<?= $baseURL ?>/flags.png?cr=<?= $k ?>"><?php endif ?><span><?= $v ?></span></button>
  <?php endif ?>
<?php endforeach ?>
</div>

<script>
$(function () {
  $('div.buttonpanel').tooltip({
    show: {delay: 600},
    items:"button.country-buttons:has(div)", 
    content: function() { return $("div", this).html(); }
  });
});
</script>
