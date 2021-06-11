<?php

header("HTTP/1.0 404 Not Found");
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../03/headerFooter');
$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : 'en';
require (__DIR__.'/resources/SuperTemplates.php');
                
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?= $lang['main_title'] ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="icon" type="image/gif" href="<?= $staticURL ?>/img/<?= $themeURL ?>/favicon.png">

<?php if ($themeURL == 'ecb') :?>
  <link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,800&amp;subset=latin,greek-ext,cyrillic-ext,greek,latin-ext,cyrillic">
<?php else :?>
  <link rel="stylesheet" type="text/css" href="<?= $staticURL ?>/fonts/allOECDfonds.css" />
<?php endif ?>

  <link rel="stylesheet" type="text/css" href="<?= $vendorsURL ?>/normalize-css/normalize.css" />
  <link rel="stylesheet" type="text/css" href="<?= $liveURL ?>/03resources/css/<?= $themeURL ?>.css" />

  <script src='<?= $vendorsURL ?>/jquery/jquery.min.js'></script>

  <script>

    $(function() {
      $('#aboutLink').hide();
      $('#shareLink').hide();
    });

  </script>

<?php
  require(__DIR__.'/../03/analytics/404/'.$themeURL.'.php');
?>
 
  <style>

    .indexText {
      padding-left: 4%;
      padding-right: 3%;
      padding-top: 4px;
    }


    #container {
      height: 400px;
      margin-top: 20px;
    }

    .container_wrapper {
      width: 98%;
      margin-left: 1%;
      margin-right: 1%;
    }


    .mainTitle {
      margin-top: 50px;
      margin-left: 20px;
    }


    #mainText {
      margin-top: 50px;
      margin-left: 20px;
      font-size: 16px;
    }

    .legendleft {
      margin-left: 20px;
    }

    .langbutton {
      text-decoration: none;
    }

    .buttondefault,
    .buttonactivebars {
      color: #ffffff;
      background-color: #0072be;
    }


  </style>

</head>

<body>

<?php
  require("header.php");
?>    
  <div class="main-container main">
    <div class="main wrapper clearfix">
      <div class="container_wrapper">
          <h1 class="mainTitle">Not Found</h1>
          <div id="mainText">
            <p>We are sorry, this link seems to be broken.</p>
    
<?php if ($themeURL == 'ecb') :?>
            <p>Please select your topic from <a href='<?=$baseURL?>'>Our Statistics - Home</a></p>
<?php else :?>
            <p>Please select your country and your topic from <a href='<?= $baseURL ?>'>Compare your country - Home</a></p>
<?php endif ?>
    
          </div>
      </div>
    </div> <!--#main-->
  </div> <!-- #main-container -->

<?php
  require("footer.php");
?>    
    
</body>
</html>
