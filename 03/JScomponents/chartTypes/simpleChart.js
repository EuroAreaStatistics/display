
var height = "innerHeight" in window
               ? window.innerHeight
               : document.documentElement.offsetHeight;

var width = "innerWidth" in window
               ? window.innerWidth
               : document.documentElement.offsetWidth;


$(document).ready(function() {

  var config = wizardConfig;
  $('.simpleChartHeight').css({height:height-2});

  var tab = config.project.tabs[0];
  var displayChart = config.tabs[config.project.tabs[0]].charts[0];
  if (urlQuery.chart != undefined  && urlQuery.chart != 'undefined' && urlQuery.chart != 'null') {
        displayChart = urlQuery.chart;
  }

  var values = config.charts[displayChart];

  //if (urlQuery.title != undefined  && urlQuery.title != 'undefined' && urlQuery.title != 'null') {
  //  $('#chartTitle').text(urlQuery.title);
  //} else {
  //  $('#chartTitle').text(values.title[lang]);
  //}
  //
  //if (urlQuery.subtitle != undefined  && urlQuery.subtitle != 'undefined' && urlQuery.subtitle != 'null') {
  //  $('#chartSubTitle').text(urlQuery.subtitle);
  //} else {
  //  $('#chartSubTitle').text(values.definition[lang]);
  //}

  var color = "#852780";
  if (urlQuery.color != undefined  && urlQuery.color != 'undefined') {
      if (urlQuery.color == 'blue') {
        color = "#04629a"
      } else if (urlQuery.color == 'green') {
        color = "#8cc841"
      } else if (urlQuery.color == 'red') {
        color = "#CD351c"
      }
  }

  $('header').css({"background-color": color});
  $('body').css({"border-color": color});


  if (urlQuery.yr != undefined  && urlQuery.yr != 'undefined') {
      var selectedYears = urlQuery.yr.split('+');
      values = filterYear(values,selectedYears);
  }

  var tabLabels = config.tabs[tab].labels;
  var translatedLabels = window.lang_countries;

  if (tabLabels) {
      $.each(tabLabels, function(key,value){
          translatedLabels[key.toLowerCase()] = value[lang];
      })
  }

	var labelValues = getChartWithLabels(values,translatedLabels);
    
  if (urlQuery.template == 'sbardiamond') {
      addBarsDiamondsChart(labelValues,'container');
  } else if (urlQuery.template == 'sstackedcolumn') {
      addStackedColumnChart(labelValues,'container');
  } else if (urlQuery.template == 'sstackedbar') {
      addStackedBarChart(labelValues,'container');
  } else if (urlQuery.template == 'slines') {
      labelValues.data.keys[1] = labelValues.data.keys[1].map(function (y) {
        return y.substring(5,7)+' '+y.substring(0,4);
      });
      addLineChart(labelValues,'container');
      // hide countries except for EUR
      Highcharts.charts[0].series.forEach(function (s) {
        if (translatedLabels.eur !== s.name) {
          s.setVisible(false, false);
        }
      });
      Highcharts.charts[0].redraw();
  } else if (urlQuery.template == 'sbarflags') {
      addBarChartFlag(values,'container',translatedLabels);
  } else {
      addBarChart(labelValues,'container');
  }

    //highlightDataPoint('usa', 'green',0,'#container');
    //highlightDataPoint('deu', 'red',0,'#container');
    //highlightDataPoint('usa', 'green',1,'#container');
    //highlightDataPoint('deu', 'red',1,'#container');

});





