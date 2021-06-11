
function mapTeaserExportRestrictions(country) {

//global vars
//  lang_labels
//  DataforMap
//  lang

  var labels2 = {};

  labels2['en'] = {
		1:'export prohibition',
		2:'export quota',
		3:'export tax',
		4:'non-automatic licencing',
		5:'other measures',
		6:'no restrictions',
    undefined:'not reseached'
	};

  labels2['cn'] = {
    1:'禁止出口',
    2:'出口配额',
    3:'出口税',
    4:'非自动许可',
    5:'其他措施',
    6:'无限制',
    undefined:'没有研究'
  };

  var teaserTitleStart = '<h3 class="tooltipTitle">';
  var teaserTitleEnd = '</h3>';
  var teaserTableStart = '<table class="tooltipTable">';
  var teaserTableLineCenter = '</td><td  class="tooltipData">';
  var teaserTableEnd = '</td></tr></table>';

  var countryProfile = "<div class='countryProfilLink'>"+
          "<span value='"+country+"' id='countryProfileMap'>"+lang_labels.countryProfile+"</span>"+
          "</div>";

  var countryName = lang_countries[country.toLowerCase()]+' - '+DataforMap[mapLayerIndex]['title'];

  var tableLines;
  var tableLine = [];

  if (DataforMap1[mapLayerMatchIndex[mapLayerIndex]-1]) {
    tableLine[0] =  "<tr>"+
                      "<td>"+DataforMap[mapLayerIndex]['options']['tooltipUnit']+":</td>"+
                      "<td class='tooltipData'>"+getHumanReadData(DataforMap[mapLayerIndex]['values'][country][1])+" "+DataforMap[mapLayerIndex]['unit']+"</td>"+
                    "</tr>";
    tableLine[1] =  "<tr>"+
                      "<td>"+DataforMap1[mapLayerMatchIndex[mapLayerIndex]-1]['options']['tooltipUnit']+":</td>"+
                      "<td class='tooltipData'>"+labels2[lang][DataforMap1[mapLayerMatchIndex[mapLayerIndex]-1]['values'][country][1]]+"</td>"+
                    "</tr>";
  } else {
    tableLine[0] =  "<tr>"+
                      "<td>"+DataforMap[mapLayerIndex]['options']['tooltipUnit']+":</td>"+
                      "<td class='tooltipData'>"+labels2[lang][DataforMap[mapLayerIndex]['values'][country][1]]+"</td>"+
                    "</tr>";
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


