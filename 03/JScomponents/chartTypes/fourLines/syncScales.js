function syncScales() {
  var min = null, max = null;
  var i;
  for(i in charts) {
    $.each(charts[i].chart.series, function(idx, series) {
      if (series.visible && (("id" in series.options) || ("tag" in series.options))) {
        var id = series.options.id || series.options.tag;
        var minValue, maxValue;
        if ("dataMin" in charts[i].data[id]) {
          minValue = charts[i].data[id].dataMin;
          maxValue = charts[i].data[id].dataMax;
        } else {
          minValue = default_min;
          maxValue = default_max;
          $.each(charts[i].data[id].data, function(idx, datapoint) {
            if ($.isArray(datapoint)) {
              datapoint = datapoint[1];
            }
            if (datapoint == null) {
              return;
            }
            if (minValue == null || datapoint < minValue) {
              minValue = datapoint;
            }
            if (maxValue == null || datapoint > maxValue) {
              maxValue = datapoint;
            }
          });
          // save min and max values for redrawing
          charts[i].data[id].dataMin = minValue;
          charts[i].data[id].dataMax = maxValue;
        }
        if (minValue != null && (min == null || minValue < min)) {
          min = minValue;
        }
        if (maxValue != null && (max == null || maxValue > max)) {
          max = maxValue;
        }
      }
    });
  }
  for(i in charts) {
    charts[i].chart.yAxis[0].setExtremes(min, max);
  }
}
