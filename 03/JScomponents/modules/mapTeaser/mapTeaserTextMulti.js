

function mapTeaserTextMulti(country) {
  //private

  var euGroup = [ 'aut', 'bel', 'bgr', 'cyp', 'deu', 'dnk', 'esp', 'fin',
                  'fra', 'gbr', 'grc', 'hrv', 'irl', 'ita', 'lux', 'lva',
                  'ltu', 'mlt', 'nld', 'prt', 'swe', 'cze', 'est', 'hun',
                  'pol', 'svk', 'svn', 'rou'];

  var teaserTitleStart = '<h3 class="tooltipTitle">';
  var teaserTitleEnd = '</h3>';
  var teaserTableStart = '<table class="tooltipTable">';
  var teaserTableLineCenter = '</td><td  class="tooltipData">';
  var teaserTableEnd = '</td></tr></table>';

  if (typeof wizardConfig !== 'undefined' && wizardConfig.tabs[wizardConfig.project.tabs[page]].countrynotes == 'withEU' && $.inArray(country.toString().toLowerCase(), euGroup) > -1) {
    var countryProfile = "<div class='countryProfilLink'>"+
            "<span value='"+country+"' id='countryProfileMap'>"+lang_labels.countryProfile+"</span>"+
            ""+
            "<span value='eu' id='euProfileMap' >view EU profile</span>"+
            "</div>";
  } else {
    var countryProfile = "<div class='countryProfilLink'>"+
            "<span value='"+country+"' id='countryProfileMap'>"+lang_labels.countryProfile+"</span>"+
            "</div>";
  }


  if (lang_countries[country.toString().toLowerCase()] != null) {
    var countryName = lang_countries[country.toString().toLowerCase()]+' - '+DataforMap[mapLayerIndex]['title'];
  } else {
    var countryName = country+' - '+DataforMap[mapLayerIndex]['title'];
  }

  var tableLines;
  var tableLine = [];

  tableLine[0] =  "<tr>"+
                    "<td>"+DataforMap[mapLayerIndex]['options']['tooltipUnit']+"</td>"+
                    "<td class='tooltipData'>"+getHumanReadData(DataforMap[mapLayerIndex]['values'][country][1])+"</td>"+
                  "</tr>";

  if (DataforMap1[mapLayerMatchIndex[mapLayerIndex]-1]) {
    tableLine[1] =  "<tr>"+
                      "<td>"+DataforMap1[mapLayerMatchIndex[mapLayerIndex]-1]['options']['tooltipUnit']+"</td>"+
                      "<td class='tooltipData'>"+getHumanReadData(DataforMap1[mapLayerMatchIndex[mapLayerIndex]-1]['values'][country][1])+"</td>"+
                    "</tr>";
  } else {
    tableLine[1] = "<tr><td></td><td></td></tr>";
  }

  tableLines = tableLine[0]+tableLine[1];

  return '' +
    teaserTitleStart +
    countryName +
    teaserTitleEnd +
    teaserTableStart +
    tableLines +
    teaserTableEnd +
    countryProfile + '';
}


