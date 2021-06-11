<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../03/headerFooter');
$language = (isset($_REQUEST['lg']) && preg_match('/^[a-z]{2}$/',$_REQUEST['lg'])) ? $_REQUEST['lg'] : 'en';
require (__DIR__.'/resources/SuperTemplates.php');
$constructMessage = $projectsWizard[$project]['underConstruction'];

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

  <link rel="stylesheet" type="text/css" href="<?= $vendorsURL ?>/css/normalize.min.css">
  <link rel="stylesheet" type="text/css" href="<?= $liveURL ?>/03resources/css/<?= $themeURL ?>.css" />

  <script src='<?= $vendorsURL ?>/jquery/jquery.min.js'></script>
  <script src="<?= $vendorsURL ?>/modernizr/modernizr.js"></script>

  <script>

    $(function() {
      $('#aboutLink').hide();
      $('#shareLink').hide();
    });

  </script>

  <style>

    .main {
       margin-top: -30px;
    }

    .container_wrapper {
      width: 98%;
      margin-left: 1%;
      margin-right: 1%;
    }

    .mainTitle {
      margin-top: 80px;
      margin-left: 20px;
    }

    #mainText {
      margin-top: 50px;
      margin-left: 20px;
      font-size: 16px;
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

        <h1 class="mainTitle">Under construction</h1>
    
        <div id="mainText">

      <p>The page '<?= $project ?>' is currently under construction.</p>

      <p><?= $constructMessage ?></a></p>

        </div>

    </div>
    
       </div> <!--#main-->
  </div> <!-- #main-container -->

    
<?php
  require("footer.php");
?>    
    
    </body>
</html>
