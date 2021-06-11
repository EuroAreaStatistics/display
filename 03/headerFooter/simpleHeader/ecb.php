
<?php

$chart = (isset($_REQUEST['chart'])&& preg_match('/^[A-Za-z0-9-_]*$/',$_REQUEST['chart'])) ? $_REQUEST['chart'] : null;

if ($chart == null || $chart == 'null') {
  $chart = $Chart[0];
}

foreach ($config['tabs'] as $tab => $value) {
  if (in_array($chart,$value['charts'])) {
    $chartTab = $tab;
  }
}


?>

<style>

body {
  border: solid;
  border-color:  #852780;
  border-width: 1px;
}

header {
  position: relative;
  background-color: #852780;
  color: #ffffff;
  height: 45px;
  z-index: 10;
}
	
#wrapper_mainNav {
  height: auto;
}

#wrapper_mainNav h1, #wrapper_mainNav h2{
  margin: 0 0 0 5px;
  width: 90%;
}

#wrapper_mainNav h1{
  font-size: 15px;
}

.subTitleShort {
  font-size: 11px;
  font-weight: normal;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.subTitleLong {
  font-size: 11px;
  font-weight: normal;
}

.main-container {
  top: 0;
  position: absolute;
}

.btn_share {
  position: absolute;
  right: 10px;
  top: 2px;
}

</style>

<script>

$(function() {

  $('header').mouseover(function(){
    $('#wrapper_mainNav h2').removeClass('subTitleShort').addClass('subTitleLong');
    $(this).css({height: "auto"})
  });

  $('header').mouseout(function(){
    $('#wrapper_mainNav h2').removeClass('subTitleLong').addClass('subTitleShort');
    $(this).css({height: "42px"})
  });


});


</script>


