<link rel="stylesheet" href="<?= $liveURL ?>/resources/ged-viz-flow1/dist/stylesheets/flow_viz.css">
<link rel="stylesheet" href="<?= $liveURL ?>/resources/jquery-ui/themes/smoothness/jquery-ui.min.css">
<style>


section.content .box .buttons button.spacer-buttons { display: none }

section.content .box .buttons button.country-buttons { height: 40px }
@media only screen and (min-width: 1024px) {
  .flow-viz-player { overflow: visible }
  .chart { display: -ms-flexbox; display: flex }
  .flow-control { display: -ms-flexbox; display: flex; -ms-flex-direction: column; flex-direction: column; -ms-flex: 0 0 170px; flex: 0 0 170px }
  #player-container { top: 0 !important; margin-top: 0 !important; width: 100% }
  #countrySelect { margin-left: 0 !important; width: 170px }
  #tabSelect { margin-left: 0 !important; width: 170px }
  section.content .box .buttons { -ms-flex-wrap: wrap !important; flex-wrap: wrap !important; width: 170px }
  section.content .box .buttons button { -ms-flex-preferred-size: 0; flex-basis: 0; min-width: 163px !important; text-align: left }
  section.content .box .buttons button.spacer-buttons { display: block; visibility: hidden; height: 0; margin: 0 7.5px 0 7.5px }
  section.content .box .buttons button.country-buttons { margin: 2px }
}

.chart {
  position: relative;
  overflow: hidden;
}

section.content .box p {
  font-size: 14px
}

header, aside.overlay {
  z-index: 120;
}

section.content .box .full {
  padding-bottom: 0;
}

#player-container {
  position: relative;
  margin: auto;
  max-width: 680px;
  height: 700px;
  top: 0;
}
@media only screen and (max-width: 540px) {
  #player-container { height: 560px; }
}

@media only screen and (max-width: 340px) {
  #player-container { height: 400px; }
}

.flow-viz-player .footer, .flow-viz-player .legend__section, .flow-viz-player .legend__toggle, .header {
  display: none;
}

.flow-viz-chart {
  bottom: 0px !important;
}

.current-date {
  display: none;
}

.contextbox, .relation-description-label {
  white-space: pre-line;
  z-index: 100;
}

.contextbox {
  padding: 0 !important;
}

.flow-viz-chart .indicator-full-label {
  text-transform: none;
  color: #2d5668;
}

.indicator .indicator-labels {
  display: none;
}

.indicator.open .indicator-labels {
  display: block;
}

.buttonactive {
  background-color: #0966ad;
}

.countrylist {
  list-style-type: none;
  padding: 0 0 2px 0;
  margin: 0;
}

.countrylist li {
  display: inline-block;
  padding: 0;
  margin: 2px;
  width: 15em;
}

.countrylist li button {
  width: 100%;
  margin: 0;
  padding: 0;
  vertical-align: top;
}

.definitionsWrapper {
  color: black;
  background: white;
  opacity: 0.85;
  position: absolute;
  top: 0;
  left: 0;
  margin: 10px;
  height: 650px;
  z-index: 100;
}

#definitions {
  overflow: auto;
}

.ui-widget {
  font-family: 'Roboto',sans-serif;
  font-size: 1em;
}

#play-button {
  font-size: 1em;
}

.ui-slider-handle {
  padding-left: 1em;
}

.ui-slider .ui-slider-handle {
  width: 6em;
}

.ui-slider-horizontal .ui-slider-handle {
  margin-left: -3em;
}

.ui-slider {
  margin: 5px 4em 5px 0;
  width: calc(100% - 18px - 6em);
  display: inline-block;
  vertical-align: middle;
}

#countrySelect {
  margin-left: 5px;
}

.embed #countrySelect {
  margin-left: 10px;
}

#tabSelect {
  z-index: 100;
  margin-bottom: 10px;
}

.legendright a {
  text-decoration: none;
}
</style>

<script src="<?= $liveURL ?>/resources/ged-viz-flow1/dist/javascripts/flow_viz.js"></script>
<script src="<?= $liveURL ?>/resources/ged-viz-flow1/dist/example_configuration/colors.js"></script>
<script src="<?= $liveURL ?>/resources/ged-viz-flow1/dist/example_configuration/locale.js"></script>
<script src="<?= $liveURL ?>/resources/ged-viz-flow1/dist/example_configuration/type_data.js"></script>
<script src="<?= $liveURL ?>/resources/jquery-ui/jquery-ui.min.js"></script>

  <div class="full">
    <div class="chart">
      <div class="full flow-control">
        <div class="dropdown blue" id="tabSelect">
<?php  foreach ($config['project']['tabs'] as $i => $t): ?>
            <span data-tab="<?= htmlspecialchars($i) ?>" title="<?= strip_tags($config['tabs'][$t]['title'][$language]) ?>"><?= strip_tags($config['tabs'][$t]['title'][$language]) ?></span>
<?php  endforeach ?>
        </div>
        <div class="dropdown blue" id="countrySelect"></div>
        <div class="buttons" id="buttons"></div>
      </div>
      <div id="player-container"></div>
      <div class="definitionsWrapper" style="display:none;">
        <div id="definitions"></div>
      </div>
    </div>
    <div>
      <div id="time-slider">
        <div class="ui-slider-handle"></div>
      </div>
      <button id="play-button"><span class="play">&#9654;</span><span class="stop" style="display:none;">&#9726;</span></button>
    </div>
    <div id="legend"></div>
    <div class="full right">
      <button id="def" title="<?= $lang['def_button'] ?>" class="definitions"></button><!--
    --><a href="<?= $baseURL.'/data_ecb?project='.$project.'&amp;tab='.$config['project']['tabs'][$page].'&amp;chart='.$config['tabs'][$config['project']['tabs'][$page]]['charts'][0] ?>" title="<?= $lang['dload_button'] ?>" class="download"></a>
    </div>
  </div>

<script>

$('#tabSelect span[data-tab="'+page+'"]').attr('data-tab', null).prependTo('#tabSelect');
$('#tabSelect').on('click', 'span', function() {
  $(this).parent().toggleClass('open');
  var value = $(this).attr('data-tab');
  if (value != null) {
    var url = '/' + project + '?cr=' + urlcountry + '&lg=' + lang + '&page=' + value;
    if (embed) {
      url += '&embed=1';
    }
    window.location = url;
  }
});

if (themeURL === 'ecb' && urlcountry === 'oecd') {
  urlcountry = 'eur';
}

if (Object.keys(lang_countries).length > 1) {
  $('#countrySelect').append(
    $.map(lang_countries, function (name, code) {
      return $('<span>').attr('data-value', code).text(name).attr('title', name);
    })
  ).prepend($('#countrySelect span[data-value="'+urlcountry+'"]').attr('data-value', null));
  $('#countrySelect span').click(function () {
    $(this).parent().toggleClass('open');
    var v = $(this).attr('data-value');
    if (v != null) {
      var url = '?page='+page+'&lg='+lang+'&cr='+v;
      if (embed) {
        url += '&embed=1';
      }
      window.location = url;
    }
  });
} else {
  $('#countrySelect').hide();
}

exampleLocale.chart.element_count_error = 'Please select at least one sector.';

exampleLocale.entityImages = {};
$.each(wizardConfig.tabs[wizardConfig.project.tabs[page]].labels, function(k, v) {
  lang_countries[k.toLowerCase()] = $('<div>').html(v[lang]).text();
  exampleLocale.entityImages[k.toLowerCase()] = '<?= $liveURL ?>/02resources/img/ecb/flow-'+k.toLowerCase()+'.svg';
});
exampleLocale.entityNames = lang_countries;

var DataforFlow = {};
var selectedCountries = ['usa','chn'];
if ('options' in wizardConfig.tabs[wizardConfig.project.tabs[page]]) {
  selectedCountries = wizardConfig.tabs[wizardConfig.project.tabs[page]].options.selected.map(function (v) {
    return v.toLowerCase();
  });
}
var selectedYear = -1; // latest
updateFlowData ('' + wizardConfig.project.tabs[page] + '_' + wizardConfig.tabs[wizardConfig.project.tabs[page]].charts[0]+'_'+urlcountry);

function addViz(countries) {
  'use strict';

  var chart = wizardConfig.charts[wizardConfig.tabs[wizardConfig.project.tabs[page]].charts[0]];

  exampleLocale.not_available = '-';

  exampleTypeData.units['viz_flow_unit'] = {
    "representation": "absolute"
  };

  exampleLocale.units["viz_flow_unit"] = {
     "full": $('<div>').html(chart.flow.unit.full[lang]).text(),
     "value": $('<div>').html(chart.flow.unit.value[lang]).text(),
     "value_html": $('<div>').html(chart.flow.unit.value[lang]).text().replace('%{number}', "<span class='value'>%{number}</span>")
  };

  exampleTypeData.units['viz_indicator_unit'] = {
    "representation": "absolute"
  };

  exampleLocale.value_in_unit = "";
  exampleLocale.units["viz_indicator_unit"] = {
     "short": "",
     "full": $('<div>').html(chart.flow.unit.full[lang]).text(),
     "value": "",
     "value_html": $('<div>').html(chart.flow.unit.value[lang]).text().replace('%{number}', "<span class='value'>%{number}</span>")
  };

  exampleColors.magnets["viz_flow"] = {
    "incoming": "#67afe5",
    "outgoing": "#0966ad"
  };

  exampleLocale.dataType["viz_flow"] = "";
  exampleLocale.flow["viz_flow"] = {
    "incoming": $('<div>').html(chart.flow.incoming[lang]).text(),
    "outgoing": $('<div>').html(chart.flow.outgoing[lang]).text(),
    "fromTo": $('<div>').html(chart.flow.fromTo[lang]).text()
  };
  exampleLocale.data["viz_flow"] = {
    "magnet": "",
    "flow": ""
  };
  exampleLocale.sources.data["viz_flow"] = {
    "name": "",
    "url": "",
  };

  var t = $('<div>').html(chart.flow.context.relation[lang]).text().match(/([^]*?[\n.])(.*%{percent[^]*)/);
  exampleLocale.contextbox.relation["viz_flow"] = t[1];
  exampleLocale.contextbox.relationPercentage["viz_flow"] = t[2];
  exampleLocale.contextbox.magnet["viz_flow"] = $('<div>').html(chart.flow.context.magnet[lang]).text();
  exampleLocale.contextbox.magnet_missing.outgoing["viz_flow"] = $('<div>').html(chart.flow.missing.outgoing[lang]).text();
  exampleLocale.contextbox.magnet_missing.incoming["viz_flow"] = $('<div>').html(chart.flow.missing.incoming[lang]).text();

  exampleLocale.indicators["viz_indicator"] = {
    "short": "",
    "full": $('<div>').html(chart.indicator.title[lang]).text(),
  };
  exampleLocale.sources.indicator["viz_indicator"] = {
    "name": "",
    "url": ""
  };

  exampleColors.magnets["viz_flow_row"] = exampleColors.magnets["viz_flow"];
  exampleLocale.dataType["viz_flow_row"] = exampleLocale.dataType["viz_flow"];
  exampleLocale.flow["viz_flow_row"] = exampleLocale.flow["viz_flow"];
  exampleLocale.data["viz_flow_row"] = exampleLocale.data["viz_flow"];
  exampleLocale.sources.data["viz_flow_row"] = exampleLocale.sources.data["viz_flow"];
  exampleLocale.contextbox.relation["viz_flow_row"] = exampleLocale.contextbox.relation["viz_flow"];
  exampleLocale.contextbox.relationPercentage["viz_flow_row"] = exampleLocale.contextbox.relationPercentage["viz_flow"];
  exampleLocale.contextbox.magnet["viz_flow_row"] = exampleLocale.contextbox.magnet["viz_flow"];
  exampleLocale.contextbox.magnet_missing.outgoing["viz_flow_row"] = exampleLocale.contextbox.magnet_missing.outgoing["viz_flow"];
  exampleLocale.contextbox.magnet_missing.incoming["viz_flow_row"] = exampleLocale.contextbox.magnet_missing.incoming["viz_flow"];
  if ("magnetROW" in chart.flow.context) {
    exampleLocale.contextbox.magnet["viz_flow_row"] = $('<div>').html(chart.flow.context.magnetROW[lang]).text();
  }

  if (selectedYear < 0 || selectedYear >= window.DataforFlow.keys[2].length) {
    selectedYear = window.DataforFlow.keys[2].length - 1;
  }

  var presentationData = {
    keyframes: updateKeyframes(countries, selectedYear),
    colors: exampleColors,
    locale: exampleLocale,
    typeData: exampleTypeData,
    decimals: 1
  };

  var data = window.DataforFlow;
  $.each(data.keys[0], function (key, crs) {
    if (crs == 'WLD') return;
    var b = $('<button class="country-buttons">')
      .attr('id', crs)
      .text(lang_countries[crs.toLowerCase()] || 'undefined')
      .attr('title', lang_countries[crs.toLowerCase()] || 'undefined');
    if ($.inArray(crs.toLowerCase(), selectedCountries) > -1) {
      b.addClass('buttonactive');
    }
    b.prepend('<svg viewBox="0 0 40 30" width="40" height="30" style="vertical-align:middle"><defs> <filter id="alpha-'+crs.toLowerCase()+'"> <feColorMatrix in="SourceGraphic" type="matrix" values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 1 0"/> </filter><mask id="mask-'+crs.toLowerCase()+'"><image xlink:href="<?= $liveURL ?>/02resources/img/ecb/flow-'+crs.toLowerCase()+'.png" height="30" width="30" y="0" x="0" filter="url(#alpha-'+crs.toLowerCase()+')"/></mask></defs> <rect mask="url(#mask-'+crs.toLowerCase()+')" height="30" fill="currentColor" width="30" y="0" x="0"/> </svg>');
    $('#buttons').append(b);
  });
  for (var i=0; i<8; i++) $('#buttons').append('<button class="spacer-buttons">');
//  window.setTimeout(function () { $('svg').map(function () { this.setAttribute('width', this.getAttribute('width')); }); }, 0); // fix missing images in buttons on Edge 16/17 - timeout 0 not enough, only some images are fixed
  $('#legend')
    .empty()
    .append($('<p style="text-align: center; color: #ffffff">')
              .append($('<span style="display: inline-block; padding: .25em 0 .25em; width: 8em">')
                        .css('background-color', exampleColors.magnets["viz_flow"].outgoing)
                        .text(exampleLocale.flow["viz_flow"].outgoing))
              .append($('<span style="display: inline-block; padding: .25em 0 .25em; width: 8em">')
                        .css('background-color', exampleColors.magnets["viz_flow"].incoming)
                        .text(exampleLocale.flow["viz_flow"].incoming)))
    .append($('<p style="text-align: center">')
              .text($('<div>').html(chart.title[lang]).text()));
  $('#definitions').html(wizardConfig.project.definition[lang]);
  var player;
  var render = function () {
    player = FlowViz.renderPlayer('#player-container', presentationData);
  };

  var resizeLabels = function () {
    window.setTimeout(function () {
      var els = player.view.chart.elements.filter(function (e) { return e.indicators.countryLabel != null; });
      // overlap is only possible for 9 or more elements
      if (els.length >= 9) {
        var boxes = els.map(function (e) {
          var box = e.indicators.countryLabel.getBBox();
          var x = e.indicators.countryLabelX;
          var y = e.indicators.countryLabelY;
          return {left: x, top: y, right: x + box.width, bottom: y + box.height};
        });
        els.forEach(function (e, idx) {
          var next = (idx + 1) % els.length;
          // use smaller font if labels overlap
          if (boxes[idx].top < boxes[next].bottom &&
              boxes[idx].bottom > boxes[next].top &&
              boxes[idx].left < boxes[next].right &&
              boxes[idx].right > boxes[next].left) {
            e.indicators.countryLabel.attr('font-size', '13px');
            els[next].indicators.countryLabel.attr('font-size', '13px');
          }
        });
      }
    }, 0);
  };

  render();
//  resizeLabels();

  if (data.keys[2].length > 1) {
    $('#time-slider').slider({
      min: 0,
      max: data.keys[2].length-1,
      value: selectedYear,
      create: function() {
        $(this).find('.ui-slider-handle').text(data.keys[2][selectedYear]);
      },
      slide: function(ev, ui) {
        $(this).find('.ui-slider-handle').text(data.keys[2][ui.value]);
        var timer = $('#play-button').attr('data-timer');
        if (timer) {
          window.clearInterval(timer);
          $('#play-button').removeAttr('data-timer');
          $('#play-button .play').show();
          $('#play-button .stop').hide();
        }
      },
      change: function(ev, ui) {
        $(this).find('.ui-slider-handle').text(data.keys[2][ui.value]);
        if (ui.value !== selectedYear) {
          selectedYear = ui.value;
          var newData = updateKeyframes(selectedCountries, selectedYear);
          player.model.loadKeyframes(newData);
//          resizeLabels();
        }
      }
    });
    $('#play-button').click(function () {
      var button = this;
      var timer = $(button).attr('data-timer');
      if (timer) {
        window.clearInterval(timer);
        $(button).removeAttr('data-timer');
        $('.play', button).show();
        $('.stop', button).hide();
      } else {
        $('.play', button).hide();
        $('.stop', button).show();
        var start = $('#time-slider').slider('value');
        var min = $('#time-slider').slider('option', 'min');
        var max = $('#time-slider').slider('option', 'max');
        var pos = start;
        if (start == max) {
          start = min;
          pos = start;
        } else {
          pos++;
        }
        $('#time-slider').slider('value', pos);
        timer = window.setInterval(function () {
          pos++;
          if (pos > max) {
            window.clearInterval(timer);
            $(button).removeAttr('data-timer');
            $('.play', button).show();
            $('.stop', button).hide();
          } else {
            $('#time-slider').slider('value', pos);
          }
        }, 2000);
        $(button).attr('data-timer', timer);
      }
    });
  }

  $('button.country-buttons').click(function(){
    var country = $(this).attr("id").toLowerCase();
    if ($.inArray(country, selectedCountries) > -1) {
      $(this).removeClass('buttonactive');
      selectedCountries.splice(selectedCountries.indexOf(country),1);
    } else {
      $(this).addClass('buttonactive');
      selectedCountries.push(country);
    }
    var newData = updateKeyframes(selectedCountries, selectedYear);
    player.model.loadKeyframes(newData);
//    resizeLabels();
  });

  $('#def').click(function(){
    $(".definitionsWrapper").toggle();
  });

};

function updateFlowData (indicator) {
  $.ajax({
    dataType: "json",
    url: baseURL+"/api-data?project="+project+"&id="+indicator,
    success: ajaxSuccess,
    error: function (xhr, ajaxOptions, thrownError) {
      if (xhr.status == '404') {
        alert ('No data available.')};
    }
  });

  function ajaxSuccess(data) {
    if (data != null) {
      window.DataforFlow=data;
      addViz(selectedCountries);
    } else {
      alert('No data available.');
    }
  }

}

function updateKeyframes(countries, year) {

  var data = window.DataforFlow;

  var newFrames = [
    {
      "date": data.keys[2][year],
      "dataType": {
        "type": "viz_flow",
        "unit": "viz_flow_unit"
      },
      "indicatorTypes": [
        {
          "type": "viz_indicator",
          "unit": "viz_indicator_unit"
        },
      ],
      "elements": [],
      "maxLabelsVisible": 9
    }
  ];

  var newData = [];
  var maxSum = 0;
  var maxBound = 0;

  $.each(countries, function (key, country) {
    newData[key]={};
    if (country.toLowerCase() === 'row') {
      newData[key]["dataType"] = {
        "type": "viz_flow_row",
        "unit": "viz_flow_unit"
      };
    }
    newData[key]["id"] = country.toLowerCase();
    newData[key]["outgoing"]={};
    var reporterCr = data.keys[0].indexOf(country.toUpperCase());
    var reporterCr1 = data.keys[1].indexOf(country.toUpperCase());
    $.each(countries, function (k, cr) {
      var partnerCr = data.keys[1].indexOf(cr.toUpperCase());
      var d = data.data[reporterCr][partnerCr][year];
      if (partnerCr === reporterCr1) {
        if (d !== null) {
          newData[key].indicators = [{
            "value": d/Math.pow(10,9),
            "missing": false
          }];
        } else {
          newData[key].indicators = [{
            "missing": true
          }];
        }
      } else if (d !== null) {
        newData[key]["outgoing"][cr.toLowerCase()] = d/Math.pow(10,9);
      }
    });

    var wld0 = data.keys[0].indexOf('WLD');
    var wld1 = data.keys[1].indexOf('WLD');

    newData[key]["sumIn"] = data.data[wld0][reporterCr1][year]/Math.pow(10,9);
    newData[key]["sumOut"] = data.data[reporterCr][wld1][year]/Math.pow(10,9);
    if (data.data[wld0][reporterCr1][year] === null) {
      newData[key]["noIncoming"] = [country.toLowerCase()];
    }
    if (data.data[reporterCr][wld1][year] === null) {
      newData[key]["noOutgoing"] = [country.toLowerCase()];
    }

    data.keys[2].forEach(function (k, yr) {
      var sumIn = data.data[wld0][reporterCr1][yr]/Math.pow(10,9);
      var sumOut = data.data[reporterCr][wld1][yr]/Math.pow(10,9);
      if (maxSum < sumIn + sumOut) {
        maxSum = sumIn + sumOut;
      }
      var intra = data.data[reporterCr][reporterCr1][yr];
      if (intra !== null) {
        intra /= 1e9;
        if (maxBound < intra) {
          maxBound = intra;
        }
      }
    });
  });

  newFrames[0].elements=newData;
  newFrames[0].maxSum=maxSum;
  newFrames[0].indicatorBounds = [[0, maxBound]];

  return newFrames;

};


</script>
