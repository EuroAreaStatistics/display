function mapTeaserIfiCountryData(country, links) {
  // private

  var teaser = $('<div>');
  var teaserTitle = $('<h3 class="tooltipTitle">');
  var teaserTable = $('<table class="tooltipTable">');
  teaser.append(teaserTitle);

  var link = links[country.toString().toLowerCase()];
  if (link != null) {
    var countryProfile = $('<div style="text-align: center;">')
      .append($('<a target="_blank">')
                .attr('href', link)
                .text(lang_labels.countryProfile));
    teaser.append(countryProfile);
  }

  var countryName = lang_countries[country.toString().toLowerCase()] || country;
  teaserTitle.text(countryName);

  var tableLineCanvas;
  var tooltipData;
  var dataValue;
  var hasData = false;
  var unit;

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
        dataValue = getHumanReadData(DataforMap[i]['values'][country][1], DataforMap[i]['options']['tooltipDecimals']);
        unit = DataforMap[i]['options']['tooltipUnit'] || '';
        if (unit !== '') {
          dataValue += '&nbsp;' + unit;
        }
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

  teaserTitle.after(teaserTable);

  // return first DOM element in jQuery object
  return teaser[0];
}
