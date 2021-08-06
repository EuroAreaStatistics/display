<?php require __DIR__.'/standardChart/CounterpartAreas.php'; ?>
<?php

//  Missing items on this template
//  - Defintions on map legend (tooltip on mousover)
//  - Option to change color coding
//  - Lang items for map (translations)
//  - Numbers format (map tooltip)
//  - Hide country borders on country coloring mode
//  - color code only countries which are present on the map

$tabID = $config['project']['tabs'][$page];

if ($chartType == 'mapWizardStandard') {
  require __DIR__.'/mapChartWizard/getSubTabs.php';
  $subTabs = getSubTabs($config);  
} else {
  $subTabs = array();
}

if (isset($config['tabs'][$tabID]['region']) && $config['tabs'][$tabID]['region'] != 'default') {
  $mapRegion = $config['tabs'][$tabID]['region'];
  $regionalMaps = TRUE;
} else {
  $mapRegion = '';
  $regionalMaps = FALSE;
}


require __DIR__.'/mapChartWizard/mapColors/mapColors.php';
if (isset($config['tabs'][$tabID]['mapColor'])) {
  // CustomMapColor from wizard one-off-settings
  $MapColors = mapColors($themeURL,$config['tabs'][$tabID]['mapColor']);
} else {
  $MapColors = mapColors($themeURL);
}


require __DIR__.'/mapChartWizard/initialMapFocus/initialMapFocus.php';
$initialMapFocus = initialMapFocus($themeURL,$regionalMaps,$mapRegion,$project,$altHeader,$config,$page);

$mapGroup = $allCountries;

if (in_array('usa', $mapGroup)) {
  $initialMapFocus = array (
    'small' => array (0,0,2),
    'large' => array (10,0,3),
 );
}

$mapVariant = NULL;
if (preg_match('/^map(NUTS[0-3])$/', $template, $matches)) {
  $mapVariant = $matches[1];
}

?>

<style>
.embed2 #colorLegend > div:last-child { display: none }
.embed3 #colorLegend > div:last-child { display: none }
.embed2 #topContainer { transform: rotate(-90deg); transform-origin: top left; left: 1em; bottom: 20%; padding: 0; height: 2em !important }
.embed3 #topContainer { transform: rotate(-90deg); transform-origin: top left; left: 1em; bottom: 20%; padding: 0; height: 1em !important }
.embed2 #legendSubtextColor { display: none !important }
.embed3 #legendSubtextColor { display: none !important }
.embed2 #colorLegend > .legendText { visibility: hidden }
.embed2 .legendText .subTitle { display: none }
.embed2 #legendTitleColor { visibility: visible; }
.embed3 .legendText { display: none }

 .related-data-control {
   background-color: #ffffff;
   width: 236px;
   padding: 3px;
}
 .related-data-control tr.accordion {
   cursor: pointer;
}
 .related-data-control td {
   padding: 2px;
   color: #333333;
}
 .related-data-control .info-icon {
   padding: 0 0 0 10px;
   width: 24px;
   height: 24px;
}
 .related-data-control .info-icon img {
   width: 24px;
   height: 24px;
   cursor: pointer;
}
.related-data-control-box {
  width: 25px;
  height: 25px;
}
.related-data-control-box div {
  display: inline-block;
  background-color: #f0f0f0;
  border: solid 1px black;
  width: 25px;
  height: 25px;
  vertical-align:middle;
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

.related-data-teasser td {
  padding: 0;
}

.related-data-teasser .tooltipTable {
  width: 100%;
}

.related-data-teasser .tooltipTable td p {
  margin: 0;
  padding: 0;
}

.map-scaler {
  width: 100%;
  min-height: 300px;
  max-height: calc(100vh - 250px);
  z-index: 3;
}

@media only screen and (max-width: 1599px) {
  .map-scaler {
    max-height: calc(100vh - 230px);
  }
}


<?php if ($altHeader) : ?>

  #map {
      top:45px;
  }

  #topContainer {
    top: auto;
    height: 20px;
    background-color: transparent;
  }

  .leaflet-control-container {
    display: none;
  }

  .legendText {
    display: none;
  }

  #colorLegend {
    margin-top: 10px;
  }

  .simpleDataSource {
    position: fixed;
    bottom: 0px;
    right: 0px;
    font-size: 10px;
    width: 100%;
    background-color: transparent;
    text-align: right;
    padding-right: 6px;
    padding-bottom: 2px;
    z-index: 99;
  }

<?php endif ?>

<?php if ($config['tabs'][$tabID]['type'] == 'flow'): ?>

  #map {
    width:80%;
    margin-left: 20%;
  }

  #flowLayer {
    width:20%;
  }

<?php endif ?>

  #map {
    min-height: 300px;
  }

<?php if ($chartType != 'mapWizardStandard') : ?>
  .countryProfilLink {
    display: none;
  }
<?php endif ?>

</style>

<?php if (isset($config['tabs'][$tabID]['type'])&&$config['tabs'][$tabID]['type'] == 'regional') : ?>
  <script src='/maps.js?type=centroidesList&amp;th=<?= $themeURL ?>&amp;vr=centroides&amp;cr=<?= $mapRegion ?>'></script>
  <script src='/maps.js?type=centroides&amp;th=<?= $themeURL ?>&amp;vr=mapCentroides&amp;cr=<?= $mapRegion ?>'></script>
  <script src='/maps.js?type=shapes&amp;th=<?= $themeURL ?>&amp;vr=mapShapes&amp;cr=<?= $mapRegion ?>'></script>
  <script src='/maps.js?type=config&amp;th=<?= $themeURL ?>&amp;vr=mapCode&amp;cr=<?= $mapRegion ?>'></script>

<?php elseif (isset($config['tabs'][$tabID]['type'])&&$config['tabs'][$tabID]['type'] == 'euShape') : ?>
  <script src='/maps.js?type=shapesWithEU&amp;th=<?= $themeURL ?>&amp;vr=mapShapes'></script>
  <script src='/maps.js?type=centroidesListEU&amp;th=<?= $themeURL ?>&amp;vr=centroides'></script>
  <script src='/maps.js?type=centroides&amp;th=<?= $themeURL ?>&amp;vr=mapCentroides'></script>
  <script src='/maps.js?type=disputed&amp;th=<?= $themeURL ?>&amp;vr=worldDisputed'></script>
  <script src='/maps.js?type=config&amp;th=<?= $themeURL ?>&amp;vr=mapCode'></script>

<?php elseif (in_array('usa', $mapGroup)): ?>
  <script src='/maps.js?type=shapes&amp;th=oecd>&amp;vr=mapShapes'></script>
  <script src='/maps.js?type=centroidesList&amp;th=oecd>&amp;vr=centroides'></script>
  <script src='/maps.js?type=centroides&amp;th=oecd>&amp;vr=mapCentroides'></script>
  <script src='/maps.js?type=disputed&amp;th=oecd>&amp;vr=worldDisputed'></script>
  <script src='/maps.js?type=config&amp;th=oecd>&amp;vr=mapCode'></script>
<?php else: ?>
  <script src='/maps.js?type=shapes&amp;th=<?= $themeURL ?>&amp;vr=mapShapes&amp;variant=<?= $mapVariant ?>'></script>
  <script src='/maps.js?type=centroidesList&amp;th=<?= $themeURL ?>&amp;vr=centroides&amp;variant=<?= $mapVariant ?>'></script>
  <script src='/maps.js?type=centroides&amp;th=<?= $themeURL ?>&amp;vr=mapCentroides'></script>
  <script src='/maps.js?type=disputed&amp;th=<?= $themeURL ?>&amp;vr=worldDisputed'></script>
  <script src='/maps.js?type=config&amp;th=<?= $themeURL ?>&amp;vr=mapCode'></script>
  <script>
    mapShapes.features.forEach(function (f) {
      if (f.properties && f.properties.NUTS_NAME && !(f.properties[mapCode].toLowerCase() in lang_countries)) {
        lang_countries[f.properties[mapCode].toLowerCase()] = f.properties.NUTS_NAME;
      }
    );
  </script>
<?php endif ?>

<?php if ($themeURL == 'ecb') : ?>
  <script src='<?= $liveURL ?>/resources/proj4/dist/proj4.js'></script>
  <script src='<?= $liveURL ?>/resources/proj4leaflet/src/proj4leaflet.js'></script>
<?php endif ?>

<ul class="tabs">
<?php  foreach ($config['project']['tabs'] as $i => $t): ?>
            <li data-tab="<?= htmlspecialchars($i) ?>"><?= strip_tags($config['tabs'][$t]['title'][$language]) ?></li>
<?php  endforeach ?>
</ul>
<ul class="tabcontent">
  <li class="active">
    <div id='main'>
<?php
  if ($config['tabs'][$tabID]['type'] != 'flow') {
    if (isset($config['tabs'][$tabID]['specialLegend'])) {
      require __DIR__.'/mapChartWizard/specialLegends/'.$config['tabs'][$tabID]['specialLegend'].'.php';
    } else {
      require __DIR__.'/mapChartWizard/topContainer.php';
    }
  }

?>
<?php if ($config['tabs'][$tabID]['type'] == 'flow') {require __DIR__.'/mapChartWizard/flowContainer.php';} ?>
    <img src="<?= $staticURL ?>/img/16x9.png" class="map-scaler" alt="">
    <div id='map'></div>

<?php if ($altHeader) {require __DIR__.'/mapChartWizard/simpleDataSource.php';}?>

    </div>
  </li>
</ul>

<script>

var chartType       = <?= json_encode($chartType) ?>;
var MapColors       = <?= json_encode($MapColors) ?>;
var bubbleMap       = <?= json_encode($bubbleMap) ?>;
var MapGroup        = <?= json_encode($mapGroup) ?>;
var initialMapFocus = <?= json_encode($initialMapFocus) ?>;

var subTabs = <?= json_encode($subTabs) ?>;

var DataforMapAll = getMapData (wizardConfig,page,Object.keys(centroides),Chart);

var DataforMap = DataforMapAll['map'];
var DataforMap1 = DataforMapAll['map1'];
var flowStatus = DataforMapAll['flow'];

var DataforFlow;


//define map projection
if (MapGroup.indexOf('usa') == -1) {
  L.CRS.CustomZoom = new L.Proj.CRS.TMS('EPSG:3035',
    '+proj=laea +lat_0=52 +lon_0=10 +x_0=4321000 +y_0=3210000 ' +
    '+ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs',
    [
      -8426600,
      -9526565.4062,
      17068403.7395,
      15968500
    ],
    {
      origin: [
        -8426600,
        15968500
      ],
      resolutions: [
        66145.9656252646,
        26458.386250105836,
        13229.193125052918,
        6614.596562526459,
        2645.8386250105837,
        1322.9193125052918,
        661.4596562526459,
        264.5838625010584,
        132.2919312505292,
        66.1459656252646
      ]
    });
} else {
    L.CRS.CustomZoom = L.extend({}, L.CRS.EPSG3857, {
      scale: function (zoom) {
        return 256 * Math.pow(1.5, zoom);
      }
    });
}

//initiate map, add legend and map layers
var mapdataviz;
if (MapGroup.indexOf('usa') == -1) {
  mapdataviz = L.map('map', {crs: L.CRS.CustomZoom, zoomControl: false, minZoom: 2})
                .setView([50.505, 4], 3);
  $('#map').css('background-color', '#d4ebf2');
} else {
  if ($(window).width() < 750) {
    mapdataviz = L.map('map', {crs: L.CRS.CustomZoom, zoomControl: false})
                  .setView([initialMapFocus['small'][0], initialMapFocus['small'][1]], initialMapFocus['small'][2]);
  } else {
    mapdataviz = L.map('map', {crs: L.CRS.CustomZoom, zoomControl: false})
                  .setView([initialMapFocus['large'][0], initialMapFocus['large'][1]], initialMapFocus['large'][2]);
  }
}
if (embed==='2' || embed==='3') {
  mapdataviz.attributionControl.setPrefix('');
  mapdataviz.attributionControl.addAttribution(lang_labels.mapSource);
  DataforMap.forEach(function (i) { i.definition = ''; });
}
L.control.zoom({
  zoomInTitle: lang_labels['mapZoomIn'],
  zoomOutTitle: lang_labels['mapZoomOut']
}).addTo(mapdataviz);

L.Control.RelatedData = L.Control.extend({
 initialize: function (labels, options) {
    L.Util.setOptions(this, options);
    this._labels = labels;
    $('body').on('click', '.ui-widget-overlay', function() {
       $('.ui-dialog-content').dialog('close');
    });
  },
  onAdd: function (map) {
    var $el = $('<div class="leaflet-control-layers related-data-control">');
    $el.on('dblclick', function () { return false; });
    var $table = $('<table>').appendTo($el);
    this._labels.forEach(function (entry, idx) {
      var $row = $('<tr class="accordion"><td class="related-data-control-box"><div></div></td></tr>')
        .append($('<td>').text(entry.label));
      $row.appendTo($table);
      $('<tr class="related-data-teasser"  style="display:none"><td colspan=2>').appendTo($table);
      function showData() {
        var teaserText = mapTeaserText(entry.id);
        var $teaser = $row.next();
        $teaser.children('td').html(teaserText);
        if (entry.tooltip != null) {
          $teaser.children('td').find('tr').first().append(
            $('<td class="info-icon">').append(
              $('<img alt="Info" title="Info">').attr('src', $('.info img').attr('src'))
              .on('click', function () {
                $(entry.tooltip).dialog({
                  draggable: false,
                  modal: true,
                  width: 'auto',
                  close: function () {
                    $(this).remove();
                  }
                });
              })));
        }
        $teaser.find('.tooltipTitle').hide();
        $('.ui-tooltip').hide();
        $row.siblings('.related-data-teasser').not($teaser).hide();
        $teaser.toggle();
        return false;
      }
      $row.click(showData);
    });
    return $el[0];
  },
  onRemove: function(map) {
  },
 updateColors: function (getColor) {
   var self = this;
   $('.related-data-control-box div', this.getContainer()).css('background-color', function (i) { return getColor(self._labels[i].id) || ''; });
 }
});

L.control.relatedData = function(labels, options) {
  return new L.Control.RelatedData(labels, options);
}

var counterpartAreas = <?= json_encode($counterpartAreas) ?>;
var relatedCodes = [
  'R12',
  '4A',
  '9A',
];
var related = [];
relatedCodes.forEach(function (iso) {
  var columns = 3;
  var label = lang_countries_titles[iso.toLowerCase()];
  if (label != null) {
    var areas = counterpartAreas[iso.toLowerCase()];
    var tooltip = null;
    if (areas) {
      tooltip = "<div title='"+label+"'><table autofocus class='multi-column'>";
      var i, j;
      var remainder = areas.length % columns;
      var fullRows = (areas.length - remainder) / columns;
      var rows = fullRows + (remainder ? 1 : 0);
      for (i = 0; i < rows ; i++) {
        tooltip += "<tr>";
        for (j = 0; j < columns ; j++) {
          if (i === fullRows && j === remainder) {
            break;
          }
          tooltip += "<td>";
          tooltip += areas[i + j*fullRows + Math.min(j, remainder)];
          tooltip += "</td>";
        }
        tooltip += "</tr>";
      }
      tooltip += "</table></div>";
    }
    related.push({
      id: iso,
      label: label,
      tooltip: tooltip,
    });
  }
});
if (related.length) {
  var relatedDataControl = L.control.relatedData(related, {position: 'topright'}).addTo(mapdataviz);
}

var popup = new L.Popup({autoPan: true, autoPanPaddingTopLeft: [50, 10], maxWidth: 400});
setLegend(chartType,bubbleMap);
addMapLayers (bubbleMap,mapdataviz,flowStatus);

//add lines for disputed territories
if (typeof worldDisputed !== 'undefined') {
  var disputedLinesWizard = getDisputedLines(worldDisputed,MapGroup);
}

var disputedLines = L.geoJson(
    disputedLinesWizard,  {
        style: getStyleDisputedLine,
    }).addTo(mapdataviz);

function getStyleDisputedLine(feature) {
  return {
    color: "#BBBBBB",
    weight: 1.5,
    opacity: 1,
    dashArray: [2,4],
  };
}

$( document ).tooltip({ tooltipClass: "custom-tooltip-styling" });

$(function () {
  // adjust map size after page has loaded
  setTimeout(function () { mapdataviz.invalidateSize(); }, 0);
});

</script>





