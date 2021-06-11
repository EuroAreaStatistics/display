
function labelFormat (translatedLables,labels) {
    return function () {
        //add flags with ISO codes as short form for countries
        //if (this.value.toUpperCase() in ISO3toFlags) {
        //    var flag = '<img  style="margin-bottom:-5px" height='+window.lableSize*1.5+'px src="'+baseURL+'/flags.png?cr='+window.ISO3toFlags[this.value.toUpperCase()]+'">'
        //    return     '<span style="margin-right:5px" >'+this.value+'</span>'+flag;
        //} else {
            if (translatedLables[this.value.toLowerCase()] != undefined) {
                return     '<span style="margin-right:5px" >'+translatedLables[this.value.toLowerCase()]+'</span>';
            } else {
                return     '<span style="margin-right:5px" >'+this.value.toLowerCase()+'</span>';
            }
//        }
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


function addBarChartSlider(values,container,colors,lang,labels,translatedLabels) {

    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
        values.data = sortDimension0(values.data,0,desc=true);
        sbars.loadChart(flipData(values.data),{
            highcharts :{
                title : {
                    text: values.title[lang],
                    style: labels.title,
                },

                subtitle: {
                    text: values.definition[lang],
                    style: labels.subtitle,
                },

                chart: {
                  type: 'bar',
                },
                legend: {
                    enabled: true,
                    floading: false,
                    verticalAlign: 'bottom',
                    align: 'center',
                    itemStyle : labels.legendStyle,
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            overflow: 'none',
                            crop: false,
                            allowOverlap: true,
                            style : labels.dataLabelsStyle,
                        },
                    },
                },
                yAxis: {
                    labels: {
                        enabled: false
                    },
                    gridLineWidth: 0,
                    min: values.options.minimum,
                    max: values.options.maximum,
                },
                xAxis: {
                    labels: {
                        style : labels.axisLabelsStyle,
                        formatter: labelFormat(translatedLabels,labels)
                    },
                },
                colors: colors,
                tooltip: {
                    shared: true,
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


function addLineChartSlider(values,container,colors,lang,labels,translatedLabels) {
    if (values.data.data[0] != undefined) {
        var sbars = new ChartElement();
        loadAndDisplayData();
    }
    function loadAndDisplayData() {
        sbars.loadChart(values.data,{
            highcharts :{
                title : {
                    text: values.title[lang],
                    style: labels.title,
                    },
                subtitle : {
                    text: values.definition[lang],
                    style: labels.subtitle,
                    },
                chart: {
                    type: 'line',
                },
                legend: {
                    enabled: true,
                    floading: false,
                    verticalAlign: 'bottom',
                    align: 'center',
                    itemStyle : labels.legendStyle,
                },
                plotOptions: {
                    series: {
                        markers: {
                            enabled: false,
                            states: { hover: { enabled: true } }
                        },
                        lineWidth: 4,
                    },
                },
                yAxis : {
                    min: values.options.minimum,
                    max: values.options.maximum,
                    labels: {
                        style : labels.axisLabelsStyle,
                    },
                },
                xAxis : {
                    tickmarkPlacement: 'on',
                    labels: {
                        style : labels.axisLabelsStyle
                    },
                },
                colors: colors,
                tooltip: {
                    shared: true,
//                    formatter: tooltipFormat(values,translatedLabels)
                },
            },
            CYCoptions : {
                decimals: values.options.tooltipDecimals,
            }
        });
        sbars.displayCharts(container);
    }
}




function addPieChartSlider(values,container,colors,lang,labels,translatedLabels) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
        sbars.loadChart(flipData(values.data),{
            highcharts :{
                title : {
                    text: values.title[lang],
                    style: labels.title,
                    },
                subtitle : {
                    text: values.definition[lang],
                    style: labels.subtitle,
                    },
                chart: {
                    type: 'pie',
                },
                legend: { enabled: false },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>',
                            style : labels.dataLabelsStyle,
                            distance: 10
                        },
                        size: '60%'
                    },
                },
                xAxis: {
                    labels: {
                        style : labels.axisLabelsStyle,
                        formatter: labelFormat(translatedLabels)
                    },
                },
                colors: colors,
                tooltip: {
//use special tooltip format for pie charts
                    shared: true,
                    useHTML: true,
                    headerFormat: '<small>{point.key}</small><table>',
                    pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
                    '<td style="text-align: right"><b>{point.y}'+values.options.tooltipUnit+'</b></td></tr>',
                    footerFormat: '</table>',
//                    formatter: tooltipFormat(values,translatedLabels)
                },
            },
            CYCoptions : {
                decimals: values.options.tooltipDecimals,
            }
        });
        sbars.displayCharts(container);
    }
}



function addColumnChartSlider(values,container,colors,lang,labels,translatedLabels) {
    var sbars = new ChartElement();
    loadAndDisplayData();
    function loadAndDisplayData() {
        sbars.loadChart(flipData(values.data),{
            highcharts :{
                title : {
                    text: values.title[lang],
                    style: labels.title,
                    },
                subtitle : {
                    text: values.definition[lang],
                    style: labels.subtitle,
                    },
                chart: {
                    type: 'column',
                },
                legend: {
                    enabled: true,
                    floading: false,
                    verticalAlign: 'bottom',
                    align: 'center',
                    itemStyle : labels.legendStyle,
                },
                plotOptions: {
                    column:   {stacking: 'normal'},
                },
                yAxis: {
                    labels: {
                        style : labels.axisLabelsStyle,
                    },
                    min: values.options.minimum,
                    max: values.options.maximum,
                },
                xAxis: {
                    labels: {
                        style : labels.axisLabelsStyle,
                        formatter: labelFormat(translatedLabels)
                    },
                },
                colors: colors,
                tooltip: {
                    shared: true,
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
