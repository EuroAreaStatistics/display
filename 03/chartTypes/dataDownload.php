<?php

$tabs = $config['project']['tabs'];

$tabList=[];

foreach ($tabs as $tab) {
  $tabList[$tab]=$config['tabs'][$tab]['charts'];
}

//echo'<pre>';
//print_r($tabList);
//die();

?>

<style>

  #wrapper_topicNav {
    display: none;
  }

  .downloadContent {
    margin-left: 10%;
    margin-right: 10%;
    margin-top: 60px;
  }

  @media only screen and (max-width: 650px) {
    .downloadContent {
      margin-left: 2%;
      margin-right: 2%;
      margin-top: 30px;
    }
  }

  .helptext {
    width: 100%;
    font-size: 14px;
    text-align: center;
  }
    
  h3 {
    margin-top: 20px;
    margin-bottom: 5px;
  }

  .dataTable {
    width: 100%;
  }

  .dataTable .indicatorName{
    padding: 2px;
    padding-left: 5px;
    padding-right: 5px;
    /*width: 70%;*/
    padding-right: 10px;
  }

  .dataTable .downloadLink {
    text-align: right;
    padding-right: 10px;
    width: 120px;
  }

  .dataTable tr:nth-child(even) {
    background: #dee3f1;
  }

</style>

	<div class="main-container main">
	  <div class="wrapper clearfix">
      <div class='downloadContent'>
        <div class='helptext'>Click on 'Download data' for an Excel file with all countries and all years for the selected indicator.</div>
<?php foreach ($tabs as $tab) : ?>
        <h3><?= $config['tabs'][$tab]['title'][$language] ?></h3>
        <table class='dataTable'>
          <tbody>
    <?php foreach ($config['tabs'][$tab]['charts'] as $chart) : ?>
            <tr>
              <td class='indicatorName'>
                <?= $config['charts'][$chart]['title'][$language] ?>
              </td>
              <td class='downloadLink'>
                <a href='/data?project=<?= $config['project']['url'] ?>&chart=<?= $chart ?>'>download data</a>
              </td>
            </tr>
    <?php endforeach ?>
          </tbody>
        </table>
<?php endforeach ?>
      </div>
    </div>
	</div> <!-- #main-container -->
