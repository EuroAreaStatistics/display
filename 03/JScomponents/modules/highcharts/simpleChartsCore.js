

// class for simple chart

//dependencies
//highcharts.js

// class for a collection of charts
function ChartElement() {
    var self = this;
    var chart ;
// configure, load and add chart to collection
    self.loadChart = function(data, options) {
        var newChart = new chartBasis(data, options);
        newChart.loadData();
        chart = newChart;
    };
    self.displayCharts = function(container) {
        chart.displayChart(container)
    };
}



function chartBasis(data, options) {
    var self = this;
    var loadedData = {};
    var defaults = {
        highcharts : {
            chart : {
                backgroundColor:'transparent'
            },
            plotOptions: {
                series: {
                    marker: {
                        enabled: false,
                        states: { hover: { enabled: true } }
                    },
                    pointPadding: 0,
                    borderWidth: 0,
                    connectNulls: true,
                }
            },
            exporting: { enabled: false },
            legend: { enabled: false },
            credits: { enabled: false },
            title : {text: null},
            xAxis:{
                labels: {
                    step: 1,
                    style : {fontSize: '1em'}
                    },
            },
            yAxis : {
                title: {text: null },
            },
        },
        CYCoptions : {
            typeOfFirstSeries: null,
        }
    };
// custom CYC options
    options = $.extend(true, defaults, options);

    self.loadData = function() {
        var Data=[];
        var categories = data.keys[1];
        $.each(data.keys[0], function(index, name) {
            var dataPoints =  data.data[index].map(function(y, i) {
                if (y != null) {
                    return {y: parseFloat(y.toFixed(options.CYCoptions.decimals)), id: categories[i], name: categories[i]};
                } else {
                    return {y: null, id: categories[i]};
                }

            });
            Data.push({name: name, data: dataPoints});
        });
        if (options.CYCoptions.typeOfFirstSeries != null) {
            Data[0].type = options.CYCoptions.typeOfFirstSeries;
        }

        loadedData = {cats: categories, series: Data};
    };

    self.displayChart = function(container) {
        var data = loadedData;
        if (data == null) return false;
        var seriesOptions = {
            chart:{ renderTo:container},
            xAxis:{categories:data.cats},
            series:data.series
        };
        options.highcharts = $.extend(true, options.highcharts, seriesOptions);
        new Highcharts.Chart(options.highcharts);
        return true;
    };
}


