<?php

$data = array (
  'DEU' => array (
    'Baden Wurttemberg' => array (
      'population' => 11,
      'life' => 74
    ),
    'Berlin' => array (
      'population' => 4,
      'life' => 72
    )
  ),
  'FRA' => array (
    'Ile de France' => array (
      'population' => 14,
      'life' => 73
    ),
    'Midi-Pyrennee' => array (
      'population' => 10,
      'life' => 76
    )
  ),
  
);

$min=[];
$min['population']=4;
$min['life']=72;
$max=[];
$max['population']=14;
$max['life']=76;

$range['population']=$max['population']-$min['population'];
$range['life']=$max['life']-$min['life'];

//print_r($range);
//die();

$scale['life'] = 280;
$scale['population'] = 10;

function convertValue ($value,$indicator) {
  global $min, $range, $scale;
  $newVal = (($value-$min[$indicator])/$range[$indicator])*$scale[$indicator];
  return $newVal;
}


?>

<style>

  .countryColumn {
    width: 100px;
    text-align: left;
  }

  .indicatorColumn {
    width: 300px;
    text-align: left;
  }

  text {
    display: none;
  }


</style>

<script>

$(document).ready(function() {

  $('circle').click(function() {
    var indicator = $(this).attr('name');
    $('text .'+indicator).show();
  });

});

</script>

<div class="main-container main">
  <div class="wrapper clearfix">
    <div id="teaserStory" class="teaserStory"><?= $config['tabs'][$tabID]['teaser'][$language]  ?></div>

    <table>
      <thead>
        <tr>
          <th class='countryColumn'>Country</th>
          <th class='indicatorColumn'>Indicator</th>
        </tr>
      </thead>
<?php foreach ($data as $cr => $values) : ?>       
      <tr>
        <td><?= $cr ?></td>
        <td>
          <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="300px" height="40px">
  <?php foreach ($values as $regio => $v) : ?>
            <circle name="<?= $regio ?>" cy="21" cx="<?=convertValue($v['life'],'life')+7?>" r="<?=convertValue($v['population'],'population')+7?>" stroke="grey" stroke-width="1" fill="#ffffff" />
            <text class="<?= $regio ?> text-anchor="middle" x="<?=convertValue($v['life'],'life')+7?>" y="13"  font-family="Verdana" font-size="11" color="#636363"><?= $regio ?></text>
  <?php endforeach ?>
          </svg>        
        </td>
      </tr>
<?php endforeach ?>    
      
    </table>
    </div>
  </div>
</div> <!-- #main-container -->
    
