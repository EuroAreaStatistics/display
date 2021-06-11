
<?php

$chart = (isset($_REQUEST['chart'])&& preg_match('/^[A-Za-z0-9-_]*$/',$_REQUEST['chart'])) ? $_REQUEST['chart'] : null;

if ($chart == null || $chart == 'null') {
  $chart = $Chart[0];
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
  overflow: hidden;
}
	
#wrapper_mainNav {
  height: auto;
}

#wrapper_mainNav h1, #wrapper_mainNav h2{
  margin: 0 0 0 25px;
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

#logo img {
  height: 35px;
  width: auto;
}

.main-container {
  top: 45px;
  position: absolute;
}

#shareLink {
  position: absolute;
  padding: 0;
  right: 8px;
  top: 2px;
  cursor: pointer;
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
    $(this).css({height: "45px"})
  });


});


</script>


<header>
  <div id="wrapper_mainNav">
    <div id="mainNav" class='headerContent'>
      <div id="logo">
        <img src="<?= $staticURL ?>/img/oecd/OECD_guillemets_white.png"  alt="compare your country">
      </div>
      <h1 id='chartTitle'><?= $config['charts'][$chart]['title'][$language] ?></h1>
      <h2 id='chartSubTitle' class='subTitleShort'><?= $config['charts'][$chart]['definition'][$language]?></h2>
<!--      <a href="#" class="btn_share_new" id="shareLink">
        <img src="<?= $staticURL ?>/img/oecd/share_neu.png"  alt="share">
      </a>
-->    </div>
  </div>
</header>


