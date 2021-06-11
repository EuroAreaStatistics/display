function drawFourLinesMulti(chartDefaults) {
  $.each(chartDefaults, function(idx, chartDefault) {
    $(".country-buttons[code='" + chartDefault.code + "']").addClass("buttondefault");
    $(".country-buttons[code='" + chartDefault.code.toUpperCase() + "']").addClass("buttondefault");
    chartDefaults[idx].code = chartDefault.code.toUpperCase();
  });

  // all parts of a single chart
  var default_chart_collection = {
    chart: null,   // pointer to the chart itself
    source_url: null,  // url where to get the data from
    data: null,    // data
    options: null    // chart options
  };

  // prepare all 6 chart objects
  charts = [];

  for (var i = 0; i < NumberOfFourLinesCharts; i++) {
    charts.push($.extend({}, default_chart_collection));
  }

  var default_options = {
    chart: {
      zoomType: 'x',
      resetZoomButton: { theme: { display: 'none' } },
      events: { selection: function (ev) { if (ev.xAxis) $(ev.target.container).parent().parent().find('button.reset').show(); } },
      type: 'line',
      animation: false,
      spacingBottom: 5,
      margin: margins,
      backgroundColor: 'rgba(255, 255, 255, 0.1)'
    },
    plotOptions: {
      series: {
        dashStyle: 'solid',
        borderWidth: '0',
        shadow: false,
        animation: false,
        lineWidth: lineWidth,
        connectNulls: connectNulls,
        marker: {
          enabled: !connectNulls,
          radius: 1,
          states: { hover: { enabled: true, radius: 4 } }
        }
      }
    },
    legend: { enabled: false },
    credits: { enabled: false },
    xAxis: {
      tickmarkPlacement: 'on',
      tickLength: 6,
      plotBands: plotBand,
      categories: [],
      labels: labelsxAxis,
      lineWidth: 1,
      min: xAxisMin,
    },
    yAxis: {
      title: {
        text: null, // txt_yAxis,
      },
      minPadding: 0.2,
      maxPadding: 0.2,
      labels: yAxisLabels
    },
    title: {
      text: null
    },
    exporting: { enabled: false },
    tooltip: tooltips,
    colors: colors
  };

  for (i = 0; i < NumberOfFourLinesCharts; i++) {
    charts[i]['options'] = $.extend(true, {}, default_options, {
      chart: {
        renderTo: 'container' + i
      },
      yAxis: {
        min: window['minimum' + i],
        max: window['maximum' + i],
        plotLines: plotLines[i]
      },
      tooltip: window['tooltip' + i],
      plotOptions: window['plotOptions' + i],
      xAxis: window['xAxisHide' + i]
    });

    charts[i]['source_url'] = window.DataFourLines[i];
  }

  // create data objects:
  for(i in charts) {
    charts[i].data = {};
  }

  //---------------------------------------------------------------------------

  // vars used on chartsSync
  var minValue = default_min;
  var maxValue = default_max;
  var numChartsToLoad = charts.length;

  var minValue = default_min;
  var maxValue = default_max;
  var numChartsToLoad = charts.length;
  for(i in charts) {
    (function(i) {
      function loadJSON(data){
        var key1;
        var key2;
        var ids = [];
        // key1: first non-numeric key with more than one value
        // key2: first numeric key
        // ids: array filled with 0 for storing k,v at key1,key2
        $.each(data.keys, function(k, v) {
          if ($.isNumeric(v[0])) {
            if (key2 == null) key2 = k;
          } else {
            if (key1 == null && v.length > 1) key1 = k;
          }
          ids.push(0);
        });

        // Set keys manually here

        key1 = 0;
        key2 = 1;

        var dates = [];
        if (datetime_xAxis) {
          charts[i].options.xAxis.type = 'datetime';
          charts[i].options.xAxis.dateTimeLabelFormats = datetime_formats;
          delete charts[i].options.xAxis.categories;
        }
        $.each(data.keys[key2], function(k, v) {
          if (datetime_xAxis) {
            var a = v.split('-');
            if (a.length < 2) a[1] = 1;
            else {
              var m = a[1].match(/^Q([1-4])$/);
              if (m !== null) a[1] = m[1] * 3 - 2;
            }
            dates.push(Date.UTC(a[0], a[1] - 1));
          } else {
            charts[i].options.xAxis.categories.push(v.toString());
          }
        });
        $.each(data.keys[key1], function(k, v) {
          ids[key1] = k;
          var series = { data: [], name: v };
          for (var j = 0; j < data.keys[key2].length; j++) {
            ids[key2] = j;
            // use array ids to index data.data
            var d = data.data;

            $.each(ids, function(i, v) {
              if (d === null) return false;
              d = d[v];
            });
            if (d === undefined) d = null;

            if (datetime_xAxis) d = [dates[series.data.length], d];
            series.data.push(d);
          }
          charts[i].data[series.name.toUpperCase()] = series;
        });
        // push initial data of the default countries (other series stays empty for now)

        charts[i].options.series = [];
        $.each(chartDefaults, function(idx, chartDefault) {
          var default_country_key = chartDefault.code;
          if (default_country_key != null && default_country_key.toLowerCase) default_country_key = default_country_key.toLowerCase();

          if (chartDefault.code in charts[i].data) {
            if (syncScale) {
              // set min and max values for all charts to default country
              $.each(charts[i].data[chartDefault.code].data, function(idx, datapoint){
                if ($.isArray(datapoint)) datapoint = datapoint[1];
                if (datapoint == null) return;
                if (minValue == null || datapoint < minValue) minValue = datapoint;
                if (maxValue == null || datapoint > maxValue) maxValue = datapoint;
              });

              // save min and max values for redrawing
              charts[i].data[chartDefault.code].dataMin = minValue;
              charts[i].data[chartDefault.code].dataMax = maxValue;

              // set current chart min and max values
              charts[i].options.yAxis.min = minValue;
              charts[i].options.yAxis.max = maxValue;
            }

            var chartSeries =
              {
                id: chartDefault.code,
                name: lang_countries[ default_country_key],
                data: charts[i].data[ chartDefault.code].data,
                tag: chartDefault.code
              };
            $.extend(chartSeries, chartDefault.options);
            charts[i].options.series.push(chartSeries);
          }
        });

        if (syncScale) {
          // create respective chart with respective options/data once all charts are loaded
          numChartsToLoad--;
          if (numChartsToLoad == 0) {
            for(var j in charts) {
              if (charts[j].options.yAxis.min < minValue) minValue = charts[j].options.yAxis.min;
              if (charts[j].options.yAxis.max > maxValue) maxValue = charts[j].options.yAxis.max;
            }
            for(var j in charts) {
              charts[j].options.yAxis.min = minValue;
              charts[j].options.yAxis.max = maxValue;

              charts[j].chart = new Highcharts.Chart(charts[j].options);
            }
          }
        } else {
          charts[i].chart = new Highcharts.Chart(charts[i].options);
        }
        $('#container' + i).parent().find('button.reset').click((function (i) {
          return function () {
            $(this).hide();
            charts[i].chart.zoomOut();
          };
        })(i));
      }

      if (charts[i].source_url && (typeof charts[i].source_url) != 'string') {
        loadJSON(charts[i].source_url);
      }
    })(i);
  }
}
