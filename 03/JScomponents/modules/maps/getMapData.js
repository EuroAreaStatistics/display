function getMapData(wizardConfig, page, countrylist, Chart) {
  var tab = wizardConfig.project.tabs[page];
  var mapType = wizardConfig.tabs[tab].mapType;
  var flowStatus = wizardConfig.tabs[tab].type;

  var ConfigProject = getPageCharts(wizardConfig, page);

  var DataforMap = [];
  var DataforMap1 = [];

  // set default layer to index of first element of Chart or 0
  var data = {
    defaultLayer:
      Chart != null
        ? Math.max(0, wizardConfig.tabs[tab].charts.indexOf(Chart[0]))
        : 0,
  };

  if (mapType == "TwoIndicatorsSplitOnYears") {
    // returns data of the first chart as separate maps - split on the YEARS dimension (data.keys[1])
    // returns data of the second chart as separate maps - split on the YEARS dimension (data.keys[1])

    var labels = wizardConfig.tabs[tab].labels;
    var key = 0;
    $.each(labels.order, function (order, value) {
      if ($.inArray(value, ConfigProject[0].data.keys[1]) > -1) {
        DataforMap[key] = filterYear(ConfigProject[0], [value]);
        if (labels.labels[value]) {
          DataforMap[key]["title"] = labels.labels[value].name;
          DataforMap[key]["unit"] = labels.labels[value].unit;
        } else {
          DataforMap[key]["title"] = value;
          DataforMap[key]["unit"] = "";
        }
        DataforMap[key]["code"] = value;
        DataforMap[key]["definition"] = value;
        DataforMap[key]["values"] = getCountryData(
          getLatestYearData(DataforMap[key]["data"]),
          countrylist
        );

        if (
          ConfigProject[0].options.minimum &&
          ConfigProject[0].options.minimum != null
        ) {
          DataforMap[key]["min"] = ConfigProject[0].options.minimum;
        } else {
          DataforMap[key]["min"] = minValueLatest(DataforMap[key]["values"]);
        }
        if (
          ConfigProject[0].options.maximum &&
          ConfigProject[0].options.maximum != null
        ) {
          DataforMap[key]["max"] = ConfigProject[0].options.maximum;
        } else {
          DataforMap[key]["max"] = maxValueLatest(DataforMap[key]["values"], 1);
        }
        DataforMap[key]["latestYear"] = maxValueLatest(
          DataforMap[key]["values"],
          0
        );
        key++;
      }
    });

    if (ConfigProject[1] != null) {
      $.each(ConfigProject[1].data.keys[1], function (key, value) {
        DataforMap1[key] = filterYear(ConfigProject[1], [value]);
        if (labels.labels[value]) {
          DataforMap1[key]["title"] = labels.labels[value].name;
        } else {
          DataforMap1[key]["title"] = value;
        }
        DataforMap1[key]["code"] = value;
        DataforMap1[key]["definition"] = value;
        DataforMap1[key]["values"] = getCountryData(
          getLatestYearData(DataforMap1[key]["data"]),
          countrylist
        );

        if (
          ConfigProject[1].options.minimum &&
          ConfigProject[1].options.minimum != null
        ) {
          DataforMap1[key]["min"] = ConfigProject[1].options.minimum;
        } else {
          DataforMap1[key]["min"] = minValueLatest(DataforMap1[key]["values"]);
        }
        if (
          ConfigProject[1].options.maximum &&
          ConfigProject[1].options.maximum != null
        ) {
          DataforMap1[key]["max"] = ConfigProject[1].options.maximum;
        } else {
          DataforMap1[key]["max"] = maxValueLatest(
            DataforMap1[key]["values"],
            1
          );
        }
        DataforMap1[key]["latestYear"] = maxValueLatest(
          DataforMap1[key]["values"],
          0
        );
      });
    }
  } else {
    $.each(ConfigProject, function (chart, values) {
      if (values.type != "flow") {
        DataforMap.push(
          (function () {
            return mobx.observable(
              {
                get id() {
                  return chart;
                },
                get title() {
                  return values.title[lang];
                },
                get definition() {
                  return values.definition[lang];
                },
                get options() {
                  return values.options;
                },
                get years() {
                  return values.data.keys[1];
                },
                selectedYear: (window.location.search.match(
                  /[?&]t=([^&]*)/
                ) || ["", null])[1],
                get data() {
                  return (
                    this.selectedYear === null
                      ? values
                      : filterYear(values, [this.selectedYear])
                  ).data;
                },
                get _values() {
                  return getCountryData(
                    getLatestYearData(this.data),
                    countrylist
                  );
                },
                get values() {
                  // mapRegion value is not used for computing minimum / maximum / color scale
                  if ("mapRegion" in this.options) {
                    var v = $.extend({}, this._values);
                    v[this.options.mapRegion] = getLatestYearData(this.data)[
                      this.options.mapRegion
                    ];
                    return v;
                  }
                  return this._values;
                },
                get _min() {
                  if (this.options.minimum && this.options.minimum != null) {
                    return this.options.minimum;
                  }
                  return minValueLatest(this._values);
                },
                get _max() {
                  if (this.options.maximum && this.options.maximum != null) {
                    return this.options.maximum;
                  }
                  return maxValueLatest(this._values, 1);
                },
                get latestYear() {
                  return maxValueLatest(this._values, 0);
                },
                get dMax() {
                  return Math.max(Math.abs(this._min), Math.abs(this._max));
                },
                get dMin() {
                  return -this.dMax;
                },
                get minColor() {
                  return quantize(
                    this._min,
                    this.dMin,
                    this.dMax,
                    MapColors.map(function (x, idx) {
                      return idx;
                    })
                  );
                },
                get maxColor() {
                  return quantize(
                    this._max,
                    this.dMin,
                    this.dMax,
                    MapColors.map(function (x, idx) {
                      return idx;
                    })
                  );
                },
                get colors() {
                  if (this.options.mapDivergent) {
                    // adjust color scale if min/max is assymetric
                    if (this.minColor > 0) {
                      return MapColors.slice(this.minColor);
                    } else if (this.maxColor + 1 < MapColors.length) {
                      return MapColors.slice(0, this.maxColor + 1);
                    }
                  }
                  return MapColors;
                },
                get min() {
                  if (this.options.mapDivergent) {
                    // adjust color scale if min/max is assymetric
                    if (this.minColor > 0) {
                      return (
                        this.dMin +
                        (this.minColor * (this.dMax - this.dMin)) /
                          MapColors.length
                      );
                    }
                  }
                  return this._min;
                },
                get max() {
                  if (this.options.mapDivergent) {
                    // adjust color scale if min/max is assymetric
                    if (
                      !(this.minColor > 0) &&
                      this.maxColor + 1 < MapColors.length
                    ) {
                      return (
                        this.dMax -
                        ((MapColors.length - (this.maxColor + 1)) *
                          (this.dMax - this.dMin)) /
                          MapColors.length
                      );
                    }
                  }
                  return this._max;
                },
              },
              {
                values: mobx.computed({ keepAlive: true }),
                min: mobx.computed({ keepAlive: true }),
                max: mobx.computed({ keepAlive: true }),
                colors: mobx.computed({ keepAlive: true }),
                latestYear: mobx.computed({ keepAlive: true }),
              },
              { deep: false, name: "map" }
            );
          })()
        );
      }
    });
  }

  data.map = DataforMap;
  data.map1 = DataforMap1;
  data.flow = flowStatus;

  function getPageCharts(wizardConfig, page) {
    var tab = wizardConfig.project.tabs[page];
    var charts = wizardConfig.tabs[tab].charts;

    var pageCharts = [];
    $.each(charts, function (k, v) {
      pageCharts.push(wizardConfig.charts[v]);
    });
    return pageCharts;
  }

  function minValue(series) {
    // private
    var min = null;
    $.each(series, function (k, v) {
      if (v == null) return;
      if (min == null) min = v;
      else if (min > v) min = v;
    });
    return min;
  }

  function maxValue(series) {
    // private
    var max = null;
    $.each(series, function (k, v) {
      if (v == null) return;
      if (max == null) max = v;
      else if (max < v) max = v;
    });
    return max;
  }

  function minValueLatest(series) {
    // public
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
    // public
    var max = null;
    $.each(series, function (k, v) {
      v = v[arrayValue];
      if (v == null) return;
      if (max == null) max = v;
      else if (max < v) max = v;
    });
    return max;
  }

  function getStandardDeviationWizard(series, value) {
    // public
    var devVal = [];
    $.each(series, function (k, v) {
      devVal.push(v[1]);
    });
    var average = getAverageFromNumArr(devVal, 2);
    var deviation = getStandardDeviation(devVal, 2);

    if (value == "max") {
      return average + deviation;
    }
    if (value == "min") {
      return average - deviation;
    } else {
      return null;
    }
  }

  function getCountryData(series, group) {
    // public
    var data = {};
    $.each(series, function (k, v) {
      if ($.inArray(k, group) > -1) {
        data[k] = v;
      }
    });
    return data;
  }

  return mobx.observable(
    {
      defaultLayer: data.defaultLayer,
      get map() {
        return data.map;
      },
      get map1() {
        return data.map1;
      },
      get flow() {
        return data.flow;
      },
    },
    {},
    { deep: false, name: "DataforMapAll" }
  );
}
