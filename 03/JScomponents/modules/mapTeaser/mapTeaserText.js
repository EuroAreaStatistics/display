function mapTeaserText(country) {
  // private

  var euGroup = [ 'aut', 'bel', 'bgr', 'cyp', 'deu', 'dnk', 'esp', 'fin',
                  'fra', 'gbr', 'grc', 'hrv', 'irl', 'ita', 'lux', 'lva',
                  'ltu', 'mlt', 'nld', 'prt', 'swe', 'cze', 'est', 'hun',
                  'pol', 'svk', 'svn', 'rou'];

  var teaser = $('<div>');
  var teaserTitle = $('<h3 class="tooltipTitle">');
  var teaserTable = $('<table class="tooltipTable">');
  teaser.append(teaserTitle);

  var countryProfile = $('<div class="countryProfilLink">')
    .append($('<span id="countryProfileMap">')
              .attr('value', country)
              .html(lang_labels.countryProfile));
  if (typeof wizardConfig !== 'undefined' && wizardConfig.tabs[wizardConfig.project.tabs[page]].countrynotes == 'withEU' && $.inArray(country.toString().toLowerCase(), euGroup) > -1) {
    countryProfile.append('<span value="eu" id="euProfileMap">view EU profile</span>');
  }
  teaser.append(countryProfile);

  var countryName = lang_countries[country.toString().toLowerCase()] || ISO3toFlags[country] || country;
  teaserTitle.text(countryName);

  var tableLineCanvas;
  var tooltipData;
  var dataValue;
  var hasData = false;

  for (var i = 0; i < ChartsWithData; i++) {
    if (DataforMap[i] != null) {
      tableLineCanvas = $('<tr>')
        .append($('<td>')
                  .attr('title', $('<div>').html(DataforMap[i]['definition']).text())
                  .html(DataforMap[i]['title']));

      tooltipData = $('<td class="tooltipData">');
      tableLineCanvas.append(tooltipData);

      if (DataforMap[i]['values'][country] != undefined && DataforMap[i]['values'][country][1] != null) {
        hasData = true;
        dataValue = getHumanReadData(DataforMap[i]['values'][country][1], DataforMap[i]['options']['tooltipDecimals']) + '&nbsp;' + DataforMap[i]['options']['tooltipUnit'];
        if (DataforMap[i]['values'][country][0] != DataforMap[i]['latestYear']) {
          dataValue += '&nbsp;(' + DataforMap[i]['values'][country][0] + ')';
        }
        tooltipData.html(dataValue);
      } else {
        tooltipData.text('\u2014'); // EM DASH
      }
      teaserTable.append(tableLineCanvas);
    }
  }

  if (hasData || themeURL != 'ecb') {
    teaserTitle.after(teaserTable);
  }

  // return first DOM element in jQuery object
  return teaser[0];
}
