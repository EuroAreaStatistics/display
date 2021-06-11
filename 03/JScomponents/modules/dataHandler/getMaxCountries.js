
function getMaxCountries (height,chart,urlQuery,lang) {

    var countryPreSelect = {

        en : ['OECD', 'USA', 'JPN', 'DEU', 'FRA', 'GBR', 'ITA', 'CAN', 'CHN', 'AUS', 'AUT',
        'BEL', 'BRA', 'CHE', 'CHL', 'CZE', 'DNK', 'ESP', 'EST', 'FIN', 'GRC', 'HUN',
        'IDN', 'IND', 'IRL', 'ISL', 'ISR', 'KOR', 'LUX', 'MEX', 'NLD', 'NOR', 'NZL',
        'POL', 'PRT', 'RUS', 'SVK', 'SVN', 'SWE', 'TUR', 'ZAF'],

        fr : ['OECD', 'USA', 'JPN', 'DEU', 'FRA', 'BEL', 'CAN', 'GBR', 'ITA', 'CHN', 'AUS', 'AUT',
        'BRA', 'CHE', 'CHL', 'CZE', 'DNK', 'ESP', 'EST', 'FIN', 'GRC', 'HUN',
        'IDN', 'IND', 'IRL', 'ISL', 'ISR', 'KOR', 'LUX', 'MEX', 'NLD', 'NOR', 'NZL',
        'POL', 'PRT', 'RUS', 'SVK', 'SVN', 'SWE', 'TUR', 'ZAF'],

        de : ['OECD', 'DEU', 'AUT', 'CHE', 'USA', 'JPN', 'FRA', 'GBR', 'ITA', 'CAN', 'CHN', 'AUS',
        'BEL', 'BRA', 'CHL', 'CZE', 'DNK', 'ESP', 'EST', 'FIN', 'GRC', 'HUN',
        'IDN', 'IND', 'IRL', 'ISL', 'ISR', 'KOR', 'LUX', 'MEX', 'NLD', 'NOR', 'NZL',
        'POL', 'PRT', 'RUS', 'SVK', 'SVN', 'SWE', 'TUR', 'ZAF'],

        es : ['OECD', 'MEX', 'ESP', 'CHL', 'USA', 'JPN', 'DEU', 'FRA', 'GBR', 'ITA', 'CAN', 'CHN', 'AUS', 'AUT',
        'BEL', 'BRA', 'CHE', 'CZE', 'DNK', 'EST', 'FIN', 'GRC', 'HUN',
        'IDN', 'IND', 'IRL', 'ISL', 'ISR', 'KOR', 'LUX', 'NLD', 'NOR', 'NZL',
        'POL', 'PRT', 'RUS', 'SVK', 'SVN', 'SWE', 'TUR', 'ZAF'],

        jp : ['OECD', 'USA', 'JPN', 'DEU', 'FRA', 'GBR', 'ITA', 'CAN', 'CHN', 'AUS', 'AUT',
        'BEL', 'BRA', 'CHE', 'CHL', 'CZE', 'DNK', 'ESP', 'EST', 'FIN', 'GRC', 'HUN',
        'IDN', 'IND', 'IRL', 'ISL', 'ISR', 'KOR', 'LUX', 'MEX', 'NLD', 'NOR', 'NZL',
        'POL', 'PRT', 'RUS', 'SVK', 'SVN', 'SWE', 'TUR', 'ZAF']

    };

    var countriesOnChart = Math.round(height/30);
    var dataKeys = [];
    maxCountriesOnChart = 0;
    $.each(chart.data.keys[0], function (k,v) {
      dataKeys.push(v.toUpperCase());
    });

    var selectedCountries = [];
    if (!(lang in countryPreSelect)) {
      countryPreSelect[lang] = [];
    }
    if (urlQuery.lc != undefined && urlQuery.lc != 'undefined') {
        var newCountries = urlQuery.lc.split('+');
        $.each(newCountries, function (k, v) {
          if ($.inArray(v.toUpperCase(),dataKeys) > -1)  {
            countryPreSelect[lang].unshift(v.toUpperCase());
            maxCountriesOnChart = maxCountriesOnChart +1;
          }
        });
    }

    if (maxCountriesOnChart>0 && maxCountriesOnChart<countriesOnChart) {
      countriesOnChart = maxCountriesOnChart;
    }

    $.each(countryPreSelect[lang], function (k,v) {
        if($.inArray(v,dataKeys) > -1) {
            selectedCountries.push(v);
        }
    });
    $.each(dataKeys, function (k,v) {
        if($.inArray(v,countryPreSelect[lang]) == -1) {
            selectedCountries.push(v.toUpperCase());
        }
    });

    selectedCountries = selectedCountries.slice(0, countriesOnChart);

    if (checkIfCountries(dataKeys) == true ) {
        var values = filterLocation(chart,selectedCountries);
    } else {
        var values = chart;
    }



    function checkIfCountries (dataKeys) {
        var allLabelsCountries = true;
        $.each(dataKeys, function (k,v) {
            if (v.toLowerCase() in window.lang_countries) {
            } else {
                allLabelsCountries =  false;
            }
        });
        return allLabelsCountries;
    }

    return values;

}


