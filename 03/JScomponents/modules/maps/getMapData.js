
function getMapData (wizardConfig,page,countrylist,Chart) {

  var tab = wizardConfig.project.tabs[page];
  var mapType = wizardConfig.tabs[tab].mapType;
  var flowStatus = wizardConfig.tabs[tab].type;

  var ConfigProject = getPageCharts(wizardConfig,page);

  var DataforMap = [];
  var DataforMap1 = [];
  
  var data = {};

  data['defaultLayer'] = 0;
  if (Chart != null) {
// set default layer to index of first element of Chart or 0
    data['defaultLayer'] = Math.max(0, wizardConfig.tabs[tab].charts.indexOf(Chart[0]));
  }

  if (mapType == 'TwoIndicatorsSplitOnYears') {
//returns data of the first chart as separate maps - split on the YEARS dimension (data.keys[1])
//returns data of the second chart as separate maps - split on the YEARS dimension (data.keys[1])

    var labels = wizardConfig.tabs[tab].labels;
    var key=0;
    $.each(labels.order, function (order,value) {
      if($.inArray(value, ConfigProject[0].data.keys[1]) > -1) {
        DataforMap[key]               = filterYear(ConfigProject[0],[value]);
        if (labels.labels[value]) {
          DataforMap[key]['title']      = labels.labels[value].name;
          DataforMap[key]['unit']      = labels.labels[value].unit;
        } else {
          DataforMap[key]['title']      = value;
          DataforMap[key]['unit']      = '';
        }
        DataforMap[key]['code']       = value;
        DataforMap[key]['definition'] = value;
        DataforMap[key]['values']     = getCountryData(getLatestYearData(DataforMap[key]['data']),countrylist);
  
        if (ConfigProject[0].options.minimum && ConfigProject[0].options.minimum != null) {
          DataforMap[key]['min']      = ConfigProject[0].options.minimum;
        } else {
          DataforMap[key]['min']      = minValueLatest(DataforMap[key]['values']);
        }
        if (ConfigProject[0].options.maximum && ConfigProject[0].options.maximum != null) {
          DataforMap[key]['max']      = ConfigProject[0].options.maximum;
        } else {
          DataforMap[key]['max']      = maxValueLatest(DataforMap[key]['values'],1);
        }
        DataforMap[key]['latestYear'] = maxValueLatest(DataforMap[key]['values'],0);
        key++;
      }
    });

    if (ConfigProject[1] != null) {
      $.each(ConfigProject[1].data.keys[1], function (key, value) {
        DataforMap1[key] = filterYear(ConfigProject[1],[value]);
        if (labels.labels[value]) {
          DataforMap1[key]['title']      = labels.labels[value].name;
        } else {
          DataforMap1[key]['title']      = value;
        }
        DataforMap1[key]['code']        = value;
        DataforMap1[key]['definition']  = value;
        DataforMap1[key]['values']      = getCountryData(getLatestYearData(DataforMap1[key]['data']),countrylist);
  
        if (ConfigProject[1].options.minimum && ConfigProject[1].options.minimum != null) {
          DataforMap1[key]['min']       = ConfigProject[1].options.minimum;
        } else {
          DataforMap1[key]['min']       = minValueLatest(DataforMap1[key]['values']);
        }
        if (ConfigProject[1].options.maximum && ConfigProject[1].options.maximum != null) {
          DataforMap1[key]['max']       = ConfigProject[1].options.maximum;
        } else {
          DataforMap1[key]['max']       = maxValueLatest(DataforMap1[key]['values'],1);
        }
        DataforMap1[key]['latestYear']  = maxValueLatest(DataforMap1[key]['values'],0);
      });
    }


  } else {

    var i=0;
    $.each(ConfigProject, function (chart, values) {
      if (values.type != 'flow') {
        DataforMap[i] = {};
        DataforMap[i]['colors']     = MapColors;
        DataforMap[i]['title']      = values['title'][lang];
        DataforMap[i]['definition'] = values['definition'][lang];
        DataforMap[i]['options']    = values['options'];
        DataforMap[i]['values']     = getCountryData(getLatestYearData(values['data']),countrylist);
        if (DataforMap[i]['options']['minimum'] && DataforMap[i]['options']['minimum'] != null) {
          DataforMap[i]['min']    = DataforMap[i]['options']['minimum'];
        } else {
          DataforMap[i]['min']    = minValueLatest(DataforMap[i]['values']);
      //    DataforMap[i]['min'] = getStandardDeviationWizard(DataforMap[i]['values'],'min');
        }
        if (DataforMap[i]['options']['maximum'] && DataforMap[i]['options']['maximum'] != null) {
          DataforMap[i]['max']    = DataforMap[i]['options']['maximum'];
        } else {
          DataforMap[i]['max']    = maxValueLatest(DataforMap[i]['values'],1);
      //    DataforMap[i]['max'] = getStandardDeviationWizard(DataforMap[i]['values'],'max');
        }
        if (DataforMap[i]['options']['mapDivergent']) {
          var dMax = Math.max(Math.abs(DataforMap[i]['min']), Math.abs(DataforMap[i]['max']));
          var dMin = -dMax;
          // adjust color scale if min/max is assymetric
          var minColor = quantize(DataforMap[i]['min'], dMin, dMax, DataforMap[i]['colors'].map(function (x,idx) { return idx; }));
          var maxColor = quantize(DataforMap[i]['max'], dMin, dMax, DataforMap[i]['colors'].map(function (x,idx) { return idx; }));
          if (minColor > 0) {
            dMin += minColor * (dMax - dMin) / DataforMap[i]['colors'].length;
            DataforMap[i]['colors'] = DataforMap[i]['colors'].slice(minColor);
          } else if (maxColor+1 < DataforMap[i]['colors'].length) {
            dMax -= (DataforMap[i]['colors'].length - (maxColor+1)) * (dMax - dMin) / DataforMap[i]['colors'].length;
            DataforMap[i]['colors'] = DataforMap[i]['colors'].slice(0, maxColor+1);
          }
          DataforMap[i]['max'] = dMax;
          DataforMap[i]['min'] = dMin;
        }
        DataforMap[i]['latestYear'] = maxValueLatest(DataforMap[i]['values'],0);
        if ('mapRegion' in DataforMap[i]['options']) {
          DataforMap[i]['values'][DataforMap[i]['options']['mapRegion']] = getLatestYearData(values['data'])[DataforMap[i]['options']['mapRegion']];
        }
        i = i+1;
      }
    });

  }
  
  data['map'] = DataforMap;
  data['map1'] = DataforMap1;
  data['flow'] = flowStatus;

  function getPageCharts (wizardConfig,page) {
    var tab = wizardConfig.project.tabs[page];
    var charts = wizardConfig.tabs[tab].charts;

    var pageCharts = [];
    $.each(charts, function (k,v){
      pageCharts.push(wizardConfig.charts[v]);
    });
    return pageCharts;
  };

  function minValue(series) {
    //private
    var min = null;
    $.each(series, function (k, v) {
      if (v == null) return;
      if (min == null) min = v;
      else if (min > v) min = v;
    });
    return min;
  }

  function maxValue(series) {
    //private
    var max = null;
    $.each(series, function (k, v) {
      if (v == null) return;
      if (max == null) max = v;
      else if (max < v) max = v;
    });
    return max;
  }

  function minValueLatest(series) {
    //public
    var min = null;
    $.each(series, function (k, v) {
      v = v[1];
      if (v == null) return;
      if (min == null) min = v;
      else if (min > v) min = v;
    });
    return min;
  }

  function maxValueLatest(series, arrayValue) {
    //public
    var max = null;
    $.each(series, function (k, v) {
      v = v[arrayValue];
      if (v == null) return;
      if (max == null) max = v;
      else if (max < v) max = v;
    });
    return max;
  }

  function getStandardDeviationWizard (series,value) {
    //public
    var devVal = [];
    $.each(series, function (k, v) {
        devVal.push(v[1])
    });
    var average = getAverageFromNumArr(devVal, 2 );
    var deviation = getStandardDeviation(devVal, 2 );

    if (value == 'max') {
      return (average + deviation);
    } if (value == 'min') {
      return (average - deviation);
    } else {return null;}

  }

  function getCountryData(series, group) {
    //public
    var data = {};
    $.each(series, function (k, v) {
      if ($.inArray(k, group) > -1) {
        data[k] = v;
      }
    });
    return data;
  }

  return data;
  
};


