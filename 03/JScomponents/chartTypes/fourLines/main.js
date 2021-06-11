var NumberOfFourLinesCharts;
var NumberOfColumnCharts;

if (template == 1) {
  NumberOfFourLinesCharts = Chart.length;
} else if (template == 2) {
  NumberOfColumnCharts = Chart.length;
} else if (template == 3) {
  NumberOfFourLinesCharts = Chart.length;
  NumberOfColumnCharts = Chart.length;
}

var ConfigProject = getPageCharts (wizardConfig, page);

var syncScale = wizardConfig.tabs[wizardConfig.project.tabs[page]].syncScale;

var tabID = wizardConfig.project.tabs[page];

var DataFourLines = [];
for (var i = 0; i < NumberOfFourLinesCharts; i++) {
  DataFourLines[i] = ConfigProject[Chart[i]]['data'];
}

var DataColumns = [];
for (var i = 0; i < NumberOfColumnCharts; i++) {
  DataColumns[i] = ConfigProject[Chart[i]]['data'];
}

var charts;

var plotBand = null;
var plotLines = {};
var tooltips = {enabled: true};

var lineWidth = 3;
var connectNulls = true;
var datetime_xAxis = true;

if (ConfigProject[Chart[0]].options.xaxis == 'category') {
  datetime_xAxis = false;
}

var datetime_formats = { year: '%Y' };
var labelsxAxis = {enabled: true, autoRotation: [0], style: { fontSize: '12px' }};
var yAxisLabels = {
  style: { fontSize: '12px', textOverflow: 'none', whiteSpace: 'nowrap' },
  align: 'left',
  x: 0,
  y: -3
};

var selectedCountries = [];
var selectedColors = [];
var default_min = null;
var default_max = null;

var options;
var clicked = false;
var goaldata = [];

var marginsBar = [0, 10, 10, 5];
var margins = [20, 10, 25, 10];

if (ConfigProject[Chart[0]].options.xaxis == 'category') {
  margins = [20, 10, null, 10];
}

var labelsYaxis = {enabled: false};
var gridLinesYaxis = 0;
var tooltips = true;
var xAxisMin = null;

var colors = ['#0072be', '#de3a07'];
var defaultColor = ['#c3c3c3'];
var urlcountryColor = '#0071c4';
var oecdColor = '#646464';
var SeriesColor = '#408000';
var SeriesColor1 = '#ff0000';

var tooltip = {
  shared: true,
  useHTML: true,
  headerFormat: '<small>{point.key}</small><table>',
  pointFormat: '',
  footerFormat: '</table>',
  valueDecimals: 0
};

var tooltipA = {
  shared: true,
  useHTML: true,
  headerFormat: '{point.key}<table>',
  pointFormat: '',
  footerFormat: '</table>',
  valueDecimals: 0
};

// settings for barsLines template
if (template == 3) {
  var yAxisLabels = {
    style: { fontSize: '10px' },
    align: 'right',
    x: -7,
    y: +3
  };
  var margins = [15, 10, 25, 35]; // [45, 8, 15, 25] when yAxis labels set
  var marginsBar = [15, 0, 25, 10];
  var gridLinesYaxis = 1;
  $(document).ready(function() {
    $( document ).tooltip({ tooltipClass: "custom-tooltip-styling" });
  });
}

if (themeURL == 'ecb') {
  var lineWidth = 2;
  var connectNulls = false;
  var colors = ['#004996'];
  var datetime_formats = { year: '%y' };
}


for (i = 0; i < NumberOfFourLinesCharts; i++) {
  window['tooltip' + i] = {enabled: true};
  window['maximum' + i] = null;
  window['minimum' + i] = null;
}

for (var i = 0; i < NumberOfColumnCharts; i++) {
  window['chart' + i];
  window['plotline' + i] = null;
  window['tooltipA' + i] = {enabled: true};
}

function getPageCharts (wizardConfig, page) {
  var tab = wizardConfig.project.tabs[page];
  var charts = wizardConfig.tabs[tab].charts;
  var pageCharts = {};
  $.each(charts, function (k, v){
    pageCharts[v] = wizardConfig.charts[v];
  });
  return pageCharts;
}

// check if chart has time format yyyy-Qq
function quarterlyData (chart) {
  return chart.data && /^\d{4}-Q\d$/.test(chart.data.keys[1][0]);
}

function drawFourLines() {
  drawFourLinesMulti([{code: urlcountry, options: {}}]);
}

// make a list out of <length> copies of <element>
function list(element, length) {
  var a = [];
  while(length--) {
    a.push(element);
  }
  return a;
}

function updateColumnChart (dataKey) {
  for (var i = 0; i < NumberOfColumnCharts; i++) {
    window['chart' + i].removeHighlights(SeriesColor1);
    window['chart' + i].removeHighlights(SeriesColor);
    window['chart' + i].highlightCountry(dataKey, SeriesColor1);
  }
}

function highlightCountryColumn(countryCode, seriesColor) {
  for (var i = 0; i < NumberOfColumnCharts; i++) {
    window['chart' + i].highlightCountry(countryCode, seriesColor);
  }
}

function removeCountryHighlightColumn(countryCode) {
  for (var i = 0; i < NumberOfColumnCharts; i++) {
    window['chart' + i].removeHighlightCountry(countryCode);
  }
}

function drawColumns() {
  var chartOptions = [];
  for (var i = 0; i < NumberOfColumnCharts; i++) {
    chartOptions[i] = {
      yAxis: {
        plotLines: window['plotline' + i],
        min: window['minimum' + i],
        max: window['maximum' + i]
      },
      tooltip: window['tooltipA' + i]
    };
    window['chart' + i] = new MakeChart(DataColumns[i], 'containerColumns' + i, chartOptions[i]);
    window['chart' + i].loadData();
  }
}

function compareBy(x){
  return function(a, b) {
    return (a[x] - b[x]);
  };
}

function objectUnion(obj1, obj2) {
  for (var prop in obj2) {
    if (typeof(obj2[prop]) == 'object' && obj1[prop] != undefined) {
      if (prop.indexOf('replace_') == 0) {
        obj1[prop.substring(8)] = obj2[prop];
      } else {
        obj1[prop] = objectUnion(obj1[prop], obj2[prop]);
      }
    } else {
      obj1[prop] = obj2[prop];
    }
  }
  return obj1;
}

// prints larger number in US format 10,000.23 usw.
function commaSeparateNumber(val){
  while (/(\d+)(\d{3})/.test(val.toString())){
    val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
  }
  return val;
}

// set tooltips for active charts
(function () {
  // format point with human readable numbers
  function humanPoint(pointFormat) {
    var s;
    if (this.points[0].series.tooltipHeaderFormatter) {
      s = this.points[0].series.tooltipHeaderFormatter(this.points[0]);
    } else if (pointFormat.tooltipHeaderFormatter) {
      s = pointFormat.tooltipHeaderFormatter(this.points[0]);
    } else {
      s = pointFormat.tooltipFooterHeaderFormatter(this.points[0]);
    }
    for (var i = 0; i < this.points.length; i++) {
      var options = this.points[i].series.tooltipOptions;
      s += '<tr><td style="color: ' + this.points[i].series.color;
      s += '">' + this.points[i].series.name;
      s += ': </td><td style="text-align: right"><b>';
      s += options.valuePrefix || "";
      s += getHumanReadData(this.points[i].y, options.valueDecimals);
      s += options.valueSuffix || "";
      s += '</b></td></tr>';
    }
    s += this.points[0].series.tooltipOptions.footerFormat;
    return s;
  }

  for (var i = 0; i < window.Chart.length; i++) {
    var chart = wizardConfig.charts[window.Chart[i]];
    window["tooltip" + i] = $.extend({}, tooltip, {
      pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td><td style="text-align: right"><b>{point.y}</b></td></tr>',
      valueDecimals: chart.options.tooltipDecimals,
      valueSuffix: chart.options.tooltipUnit,
      formatter: humanPoint
    });
    if (quarterlyData(chart)) {
      window["tooltip" + i].dateTimeLabelFormats = { month: '%Y-Q%Q' };
    }
    window["tooltipA" + i] = $.extend({}, tooltipA, {
      pointFormat: '<tr><td style="color: {series.color} text-align: left"><small>{point.y}</small></td></tr>',
      valueDecimals: chart.options.tooltipDecimals,
      valueSuffix: chart.options.tooltipUnit
    });
    window["minimum" + i] = chart.options.minimum;
    window["maximum" + i] = chart.options.maximum;
    if (chart.plotLines != null) {
      plotLines[i] = chart.plotLines.map(function(l) {
        return $.extend(true, {color: '#000000', dashStyle: 'Dash', width: 2, label: {align: "right", style: {color: "#606060", fontSize: "10px"}}}, l);
      });
    }
  }
})();

if (wizardConfig.tabs[tabID].plotband != undefined && wizardConfig.tabs[tabID].plotband[0] == 'projections') {
  plotBand = [{
    color: '#C6C9FF',
    from: Date.UTC(wizardConfig.tabs[tabID].plotband[1].start,0,0),
    to: Date.UTC(wizardConfig.tabs[tabID].plotband[1].end,0,0)
  }];
  $(function() {
    $('#legendleft').show();
    $('#legendleft').html("<ul><li><span class='sharebutton' style='background-color:#C6C9FF; margin-right: 5px; padding-top: 0px; padding-bottom: 0px;'></span><span style='color:#000000;'> = " + lang_labels.projections + "</span></li></ul>");
  });
}

if (wizardConfig.tabs[tabID].xAxisMin != undefined) {
  xAxisMin = wizardConfig.tabs[tabID].xAxisMin;
}


$(function() {
  drawFourLines();
  drawColumns();

  $('button.country-buttons.buttondefault')
       .attr("series", 1)
       .addClass("buttonactive")
       .removeClass("buttondefault");

// highlightCountryColumn(urlcountry.toUpperCase(), $('button.country-buttons.buttonactive').css("background-color"));
  highlightCountryColumn(urlcountry.toUpperCase(), '#0071c4');

  $('button.country-buttons').click(function(){
    var country = $(this).attr("code").toUpperCase();
    if ($(this).hasClass("buttonactive")) {
      removeCountryFromChart(country);
      removeCountryHighlightColumn(country);
      $(this).removeClass("buttonactive");
      $(this).removeAttr("series");
      if (urlcountries.indexOf(country.toLowerCase()) > -1) {
        urlcountries.splice(urlcountries.indexOf(country.toLowerCase()), 1);
      }
    } else {
      var activeSeries = {};
      $(this).closest('.buttonpanel')
             .find('button.country-buttons.buttonactive')
             .each(function() {
               activeSeries[$(this).attr("series")] = this;
             });
      var series = 1;
      while (series in activeSeries && series <= maxSeries) {
        series++;
      }
      if (series > maxSeries) {
        // remove country with maxSeries
        series = maxSeries;
        var countryToRemove = $(activeSeries[series]).attr("code").toUpperCase();
        removeCountryFromChart(countryToRemove);
        removeCountryHighlightColumn(countryToRemove);
        $(activeSeries[series]).removeClass("buttonactive");
        $(activeSeries[series]).removeAttr("series");
        if (urlcountries.indexOf(countryToRemove.toLowerCase()) > -1) {
          urlcountries.splice(urlcountries.indexOf(countryToRemove.toLowerCase()), 1);
        }
      }
      $(this).attr("series", series);
      $(this).addClass("buttonactive");
      addCountryToChart(country, $(this).css("background-color"));
      highlightCountryColumn(country, $(this).css("background-color"));
      if (urlcountries.indexOf(country.toLowerCase()) < 0) {
        urlcountries.push(country.toLowerCase());
      }
      if (series == 1) {
        urlcountry = country.toLowerCase();
      }
    }
    if (syncScale){
      syncScales();
    }
  });

  // select additional countries
  urlcountries.slice(1, maxSeries).forEach(function (code) {
    code = code.toUpperCase();
    $('button.country-buttons').filter(function() { return $(this).attr('code').toUpperCase() === code; }).filter(':visible').trigger('click');
  });

  $('#countrySelect1').change(function(){
    setData($(this).attr("value"));
    updateColumnChart($(this).attr('value'));
  });

  $.each(window.Chart, function(k, v){
    $('#DataSelect' + k).val(v);
    $('#DataSelect' + k).change(updateChart(k));
  });

  $('.DefI').hide();
  $('#def').show();
  if (themeURL != 'ecb') {
    $('#dload').hide();
  }

  $('.definitionsWrapper').hide();
  $('.downloadsWrapper').hide();

  $('#def').click(function() {
    $('.definitionsWrapper').toggle();
    $('.downloadsWrapper').hide();
  });

  $('#dload').click(function() {
    $('.downloadsWrapper').toggle();
    $('.definitionsWrapper').hide();
  });

  $('#titleDisplaySelectInstruction').show();
});
