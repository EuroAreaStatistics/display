

//highlights a data point on a highcharts chart

function highlightDataPoint(country,color,series,container) {
    $(container).highcharts().series[series].data.forEach(function(dataPoint){
        if (dataPoint.id==country.toUpperCase()) {
            dataPoint.update({color:color});
        } else if  (dataPoint.color==color) {
            dataPoint.update({color:null});
        }
    });
}
