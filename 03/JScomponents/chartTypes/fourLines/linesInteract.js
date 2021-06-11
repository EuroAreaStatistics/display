

//adds and removes series from chart

setData = (function() {
// store last country code in a local variable
  var lastCountryCode = null;
  return function(countrycode) {
    if (countrycode === '') countrycode = null;
    if (countrycode != null && countrycode.toUpperCase) countrycode = countrycode.toUpperCase();
    if (lastCountryCode != null) removeCountryFromChart(lastCountryCode);
    lastCountryCode = countrycode;
    var SeriesColor = '#E04514';
    if (countrycode != null) addCountryToChart(countrycode, SeriesColor);
    if (syncScale) {
        syncScales();
    }
  };
})();

function removeCountryFromChart(country) {
	for(i in charts) {
		if (charts[i].data[country]==undefined) continue;
		var chart=charts[i].chart;
		if (!chart) continue;
		for (var j=0;j<chart.series.length;j++) {
			if (chart.series[j].options.tag == country) {
				chart.series[j].remove();
				break;
			}
		}
	}
}

function addCountryToChart(country, SeriesColor) {
	for(i in charts) {
		if (charts[i].data[country]==undefined) continue;
		var chart=charts[i].chart;
		if (!chart) continue;
		var key=charts[i].data[country].name;
		if (key.toLowerCase) key=key.toLowerCase();
		var countryName=lang_countries[ key ];
		var countryData=charts[i].data[country].data;
		chart.addSeries({name:countryName,data:countryData,tag:country,color:SeriesColor});
	}
}

