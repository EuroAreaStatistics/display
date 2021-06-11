<?php

  $flowHelptext = array (
    'en' => 'Click on bubbles to show export flows for individual countries.',
    'cn' => '点击泡泡显示各国出口流量'
  )


?>



    <div id='flowLayer'>
      <div class=detailsCanvas>
        <h2><span class='commodity'></span></h2>
        <h3>
          <span id='countryPrefix' class='countryPrefix'></span>
          <span class='country'></span>
          <span id='countrySuffix' class='countryPrefix'></span>
        </h3>
        <div>
          <div class='helptext'><?= $flowHelptext[$language] ?></div>
          <table id='detailsTable'>
            <thead>
              <tr>
                <th class='countries'></th>
                <th class='values'></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div id='sizeLegend' class='legendItem'>
        <div class='legendText'><?= $lang['mapBubbleSize']?>: <span id='legendTitleSize'></span><span class='subTitle' id='legendUnitSize'></span>, <span class='subTitle' id='legendYearSize'></span></div>
        <div  id='legendBubble' class='legendText'></div>
      </div>
    </div>
