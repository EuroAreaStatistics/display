
var height = "innerHeight" in window
               ? window.innerHeight
               : document.documentElement.offsetHeight;

var width = "innerWidth" in window
               ? window.innerWidth
               : document.documentElement.offsetWidth;

var config = window.wizardConfig;
var tab = config.project.tabs[page];

var stackType = config.tabs[tab].stackType;

var chartGroup0 = config.charts[config.tabs[tab].charts[0]];
var indicator = chartGroup0.options.indicator;

if (stackType == 'pyramids') {
  var chartGroup1 = {};
  chartGroup1['title'] = config.charts[config.tabs[tab].charts[0]].title;
  chartGroup1['definition'] = config.charts[config.tabs[tab].charts[0]].definition;
  chartGroup1['options'] = config.charts[config.tabs[tab].charts[0]].options;
  var indicator1 = chartGroup1.options.indicator1;
}

var country0;
var country1 = 'addCountry';

$(document).ready(function() {
  if (stackType == 'pyramids') {
    updateIndicator (indicator,'pageLoad','pyramidsRight');
    updateIndicator (indicator1,'pageLoad','pyramidsLeft');
  } else if (stackType == 'lineWithBars') {
    updateIndicator (indicator,'pageLoad','lineWithBars');
  } else {
    updateIndicator (indicator,'pageLoad');
  }
  $('#chartTitle').val(config.tabs[tab].charts[0]);
  $('body').on('change','#countrySelect',function(){
    country0 = $(this).val();
    if (stackType == 'pyramids') {
      createStackedChart (window.chartGroup0,'container',country0,null,'pyramidsRight');
      createStackedChart (window.chartGroup1,'container',country0,null,'pyramidsLeft');
    } else if (stackType == 'lineWithBars') {
      createStackedChart (window.chartGroup0,'container',country0,null,'lineWithBars');
    } else {
      createStackedChart (window.chartGroup0,'container',country0);
    }
  });
  $('body').on('change','#countrySelect1',function(){
    country1 = $(this).val();
    if (stackType == 'pyramids') {
      createStackedChart (window.chartGroup0,'container1',country1,null,'pyramidsRight');
      createStackedChart (window.chartGroup1,'container1',country1,null,'pyramidsLeft');
    } else if (stackType == 'lineWithBars') {
      createStackedChart (window.chartGroup0,'container1',country1,null,'lineWithBars');
    } else {
      createStackedChart (window.chartGroup0,'container1',country1);
    }
  });
  $('#chartTitle').change(function(){
    var newindicator = $(this).val();
    indicator = config.charts[newindicator].options.indicator;
    if (stackType == 'lineWithBars') {
      chartGroup0 = config.charts[newindicator];
      updateIndicator (indicator,null,'lineWithBars');
    } else {
      updateIndicator (indicator);
    }
  });


});

function createStackedChart(chartData,container,country,mode,stackType) {
  var countries = chartData.data.keys[0];
  country = country.toString().toUpperCase();
  var chart = {};
  chart['title'] = chartData.title;
  chart['definition'] = chartData.definition;
  chart['options'] = chartData.options;
  if ($.inArray(country,countries)>-1) {
    chart['data'] = filterDimension (chartData.data,country);
  } else {
    chart['data'] = filterDimension (chartData.data,window.urlcountry);
    if (container == 'container1') {
      window.country1 = window.urlcountry;
    } else {
      window.country0 = window.urlcountry;
    }
  }
  var tabLabels = config.tabs[tab].labels;
  var translatedLabels = window.lang_countries;
  if (tabLabels) {
    $.each(tabLabels.labels, function(key,value){
      translatedLabels[key.toLowerCase()] = value.name;
    })
  }
	var values = getChartWithLabels(chart,translatedLabels);
  if (mode == 'newIndicator') {
    $('#countrySelect option').remove()
    $('#countrySelect1 option').remove()
    $('#countrySelect1').append('<option value="addCountry">'+lang_labels.addCountry+'</option>')
    $.each(lang_countries, function (key,value) {
      if ($.inArray(key.toString().toUpperCase(),countries)>-1) {
        $('#countrySelect').append('<option value='+key+'>'+value+'</option>')
        $('#countrySelect1').append('<option value='+key+'>'+value+'</option>')
      }
    });
    $('#countrySelect').val(window.country0);
    $('#countrySelect1').val(window.country1);
  }
  if (stackType == 'pyramidsRight') {
    addPyramidRight(values,container);
  } else if (stackType == 'pyramidsLeft') {
    addPyramidLeft(values,container+'A');
  } else if (stackType == 'lineWithBars') {
    addLineStackedBarChart(values,container);
  } else {
    addStackedAreaChart(values,container);
  }
}

function updateIndicator (indicator,mode,stackType) {
  $.ajax({
    dataType: "json",
    url: baseURL+"/api-data?project="+window.project+"&id="+indicator,
    success: ajaxSuccess(mode,stackType),
    error: function (xhr, ajaxOptions, thrownError) {
      if (xhr.status == '404') {
        alert ('No data available.')};
    }
  });
}


function ajaxSuccess(mode,stackType) {
  return function (data) {
    if (data != null) {
      if (stackType == 'pyramidsLeft') {
        window.country0 = window.urlcountry;
        window.chartGroup1['data'] = data;
        createStackedChart (window.chartGroup1,'container',country0,'newIndicator',stackType);
        if (mode != 'pageLoad') {
          createStackedChart (window.chartGroup1,'container1',country1,'newIndicator',stackType);
        }
      } else {
        window.chartGroup0['data'] = data;
        if ($.inArray(window.urlcountry.toString().toUpperCase(),chartGroup0.data.keys[0])==-1) {
          window.urlcountry = chartGroup0.data.keys[0][0];
        }
        window.country0 = window.urlcountry;
        createStackedChart (window.chartGroup0,'container',country0,'newIndicator',stackType);
        if (mode != 'pageLoad') {
          createStackedChart (window.chartGroup0,'container1',country1,'newIndicator',stackType);
        }
      }
      if (window.stackType == 'pyramids') {
        $( "#slider" ).slider({
          value:parseFloat(chartGroup0.data.keys[0][0]),
          min: parseFloat(chartGroup0.data.keys[0][0]),
          max: parseFloat(chartGroup0.data.keys[0][chartGroup0.data.keys[0].length-1]),
          step: 1,
          slide: function( event, ui ) {
            $( "#year" ).val(ui.value );
            createStackedChart (window.chartGroup0,'container1',ui.value,null,'pyramidsRight');
            createStackedChart (window.chartGroup1,'container1',ui.value,null,'pyramidsLeft');
          }
        });
        $( "#year" ).val($( "#slider" ).slider( "value" ) );
      }
    } else {
      alert('No data available.');
    }
  }
}


