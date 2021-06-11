

function labelFormat (translatedLables) {
    return function () {
//        add flags with ISO codes as short form for countries
      if (this.value.toUpperCase() in window.ISO3toFlags) {
        var flag = '<img  style="margin-bottom:4px" height=18px src="'+baseURL+'/flags.png?cr='+this.value.toUpperCase()+'">'
        return     '<span style="margin-right:5px" >'+translatedLables[this.value.toLowerCase()]+'</span>'+flag;
      } else {
        if (translatedLables[this.value.toLowerCase()] != undefined) {
            return     '<span style="margin-right:5px" >'+translatedLables[this.value.toLowerCase()]+'</span>';
        } else {
            return     '<span style="margin-right:5px" >'+this.value.toLowerCase()+'</span>';
        }
      }
    }
}


function tooltipFormat (values,translatedLables) {
    return function () {
        if (translatedLables[this.x.toLowerCase()] != undefined) {
            var s = '<b>' + translatedLables[this.x.toLowerCase()] + '</b>';
        } else {
            var s = '<b>' + this.x + '</b>';
        }
        $.each(this.points, function () {
            s += '<br/>' + this.series.name + ': ' +
            this.y+' '+values.options.tooltipUnit;
        });
        return s;
    }
}

//config functions for simple chart templates

//dependencies
//simpleChartsCore.js


function addLineChart(values,container) {
  Highcharts.setOptions({lang: { numericSymbols: null}});
  if (values.data.data[0] != undefined) {
    var sbars = new ChartElement();
    loadAndDisplayData();
  }
    function loadAndDisplayData() {
    sbars.loadChart(values.data,{
      highcharts :{
        chart: {
          type: 'line',
          //marginBottom: 20
        },
        legend: {
          enabled: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 10,
        },
        plotOptions: {
          series: {
          markers: {
            enabled: false,
            states: { hover: { enabled: true } }
          },
            lineWidth: 2,
          },
        },
        yAxis : {
          min: values.options.minimum,
          max: values.options.maximum,
        },
        xAxis : {
          reversed: true,
          tickmarkPlacement: 'on',
          labels: {
                    style : {"font-size":"11px"},
                    step: null,
                  },
        },
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
        colors: [ "#de3a07", "#408000", "#dca2bf", "#fdd14e", "#730000", "#ffa640", "#234010", "#6cc3d9", "#4400ff", "#f200a2", "#750039", "#33260d", "#00e61f", "#0099e6", "#1f0073", "#962c0b", "#734939", "#735c00", "#00f2a2", "#004996"],
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}

function addLineStackedBarChart(values,container) {
  if (values.data.data[0] != undefined) {
    var sbars = new ChartElement();
    loadAndDisplayData();
  }
    function loadAndDisplayData() {
    sbars.loadChart(values.data,{
      highcharts :{
        chart: {
          type: 'column',
          //marginBottom: 20
        },
        legend: {
          enabled: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 5,
        },
        plotOptions: {
          area:   {stacking: 'normal'},
          bar:   {stacking: 'normal'},
          column:   {stacking: 'normal'},
          series: {
            markers: {
              enabled: false,
              states: { hover: { enabled: true } }
            },
            lineWidth: 2,
          },
        },
        yAxis : {
          min: values.options.minimum,
          max: values.options.maximum,
        },
        xAxis : {
          tickmarkPlacement: 'on',
          labels: {
                    style : {"font-size":"11px"},
                    step: null,
                  },
        },
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
//      colors: ['#bbbbbb','#232323'],
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
        typeOfFirstSeries: values.options.typeOfFirstSeries || 'line',
      }
    });
        sbars.displayCharts(container);
    }
}

function addStackedAreaChart(values,container) {
  if (values.data.data[0] != undefined) {
    var sbars = new ChartElement();
    loadAndDisplayData();
  }
    function loadAndDisplayData() {
    sbars.loadChart(values.data,{
      highcharts :{
        chart: {
          type: 'area',
          //marginBottom: 20
        },
        legend: {
          enabled: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 5,
        },
        plotOptions: {
          area:   {stacking: 'normal'},
          series: {
            markers: {
              enabled: false,
              states: { hover: { enabled: true } }
            },
            lineWidth: 2,
          },
        },
        yAxis : {
          min: values.options.minimum,
          max: values.options.maximum,
        },
        xAxis : {
          tickmarkPlacement: 'on',
          labels: {
                    style : {"font-size":"11px"},
                    step: null,
                  },
        },
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
//      colors: ['#bbbbbb','#232323'],
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}

function addBarChart(values,container) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
    values.data = sortDimension0(values.data,0,desc=true);
    sbars.loadChart(flipData(values.data),{
      highcharts :{
        chart: {
          type: 'bar',
          marginBottom: 10
        },
//        legend: { enabled: false },
        legend: {
          enabled: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 5,
        },
        plotOptions: {
          series: {
            dataLabels: {
              enabled: true,
              overflow: 'none',
              crop: false,
              allowOverlap: true,
            },
          },
        },
        yAxis: {
          labels: {enabled: false},
          gridLineWidth: 0,
          min: values.options.minimum,
          max: values.options.maximum,
        },
        //xAxis: {
        //  labels: {useHTML: true}
        //},
//        colors: ['#88b60f','#232323'],
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}


function addBarChartFlag(values,container,translatedLabels) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
    values.data = sortDimension0(values.data,0,desc=true);
    sbars.loadChart(flipData(values.data),{
      highcharts :{
        chart: {
          type: 'bar',
          marginBottom: 10,
          marginLeft: 150
        },
//        legend: { enabled: false },
        legend: {
          enabled: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 5,
        },
        plotOptions: {
          series: {
            dataLabels: {
              enabled: true,
              overflow: 'none',
              crop: false,
              allowOverlap: true,
            },
          },
        },
        yAxis: {
          labels: {enabled: false},
          gridLineWidth: 0,
          min: values.options.minimum,
          max: values.options.maximum,
        },
        xAxis: {
          labels: {
            useHTML: true,
            formatter: labelFormat(translatedLabels)
          }
        },
//        colors: ['#88b60f','#232323'],
        tooltip: {
          shared: true,
          useHTML: true,
          formatter: tooltipFormat(values,translatedLabels)
        },
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}


function addColumnChart(values,container) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
    values.data = sortDimension0(values.data,0,desc=true);
    sbars.loadChart(flipData(values.data),{
      highcharts :{
        chart: { type: 'column'},
//        legend: { enabled: false },
        legend: {
          enabled: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 5,
        },
        plotOptions: {
          series: {
            dataLabels: {
              enabled: false,
              overflow: 'none',
              crop: false,
              allowOverlap: true,
            },
          },
        },
        yAxis: {
          min: values.options.minimum,
          max: values.options.maximum,
        },
        colors: ['#88b60f','#232323'],
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}


function addBarsDiamondsChart(values,container) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
    values.data = sortDimension0(values.data,0,desc=true);
    sbars.loadChart(flipData(values.data),{
      highcharts :{
        chart: { type: 'line',marginBottom: 20},
        legend: {
          enabled: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 5,
        },
        plotOptions: {
          series: {
            marker: {
              enabled: true,
            },
          lineWidth: 0,
          states: { hover: {
                  lineWidth: 0,
                  lineWidthPlus: 0,
                }
          }
          },
        },
        yAxis: {
          min: values.options.minimum,
          max: values.options.maximum,
        },
//        colors: ['#bbbbbb','#232323'],
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
      },
      CYCoptions : {
        typeOfFirstSeries: 'bar',
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}



function addStackedColumnChart(values,container) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
    sbars.loadChart(flipData(values.data),{
      highcharts :{
        chart: {
          type: 'column',
//          marginBottom: 20
        },
        legend: {
          enabled: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 5,
        },
        plotOptions: {
                    bar:   {stacking: 'normal'},
                    column:   {stacking: 'normal'},
        },
        yAxis: {
          min: values.options.minimum,
          max: values.options.maximum,
        },
        xAxis: {
                labels: {
                    step: null,
                    },
        },
//        colors: ['#bbbbbb','#232323'],
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}

function addStackedBarChart(values,container) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
    values.data = sortDimension0sum(values.data,desc=true);
    sbars.loadChart(flipData(values.data),{
      highcharts :{
        chart: {
          type: 'bar',
          marginBottom: 20
        },
        legend: {
          enabled: true,
          reversed: true,
          verticalAlign: 'top',
          padding: 0,
          margin: 5,
        },
        plotOptions: {
                    bar:   {stacking: 'normal'},
                    column:   {stacking: 'normal'},
        },
        yAxis: {
          min: values.options.minimum,
          max: values.options.maximum,
        },
        xAxis: {
//          opposite: true,
//          labels : {enabled: false}
        },
//        colors: ['#bbbbbb','#232323'],
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}

function addPyramidRight(values,container) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
    sbars.loadChart(flipData(values.data),{
      highcharts :{
        title: {text:'Men'},
        chart: {
          type: 'bar',
//          marginBottom: 10
        },
        legend: {
          enabled: true,
          reversed: true,
          verticalAlign: 'bottom',
          padding: 5,
          margin: 5,
        },
        plotOptions: {
          bar:   {stacking: 'normal'},
          column:   {stacking: 'normal'},
          series: {
              animation: false
          }
        },
        yAxis: {
          min: values.options.minimum,
          max: values.options.maximum,
        },
        xAxis: {
          opposite: true,
//          labels : {enabled: false}
        },
//        colors: ['#bbbbbb','#232323'],
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}


function addPyramidLeft(values,container) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
    sbars.loadChart(flipData(values.data),{
      highcharts :{
        title: {text:'Women'},
        chart: {
          type: 'bar',
        },
        legend: {
          enabled: true,
          reversed: true,
          verticalAlign: 'bottom',
          padding: 5,
          margin: 5,
        },
        plotOptions: {
          bar:   {stacking: 'normal'},
          column:   {stacking: 'normal'},
          series: {
              animation: false
          }
        },
        xAxis: {
//          reversed: true,
        },
        yAxis: {
          min: values.options.minimum,
          max: values.options.maximum,
          reversed: true,
        },
//        colors: ['#bbbbbb','#232323'],
        tooltip: {
          shared: true,
          useHTML: true,
          headerFormat: '<small>{point.key}</small><table>',
          pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
          '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
          footerFormat: '</table>',
        },
      },
      CYCoptions : {
        decimals: values.options.tooltipDecimals,
      }
    });
        sbars.displayCharts(container);
    }
}
