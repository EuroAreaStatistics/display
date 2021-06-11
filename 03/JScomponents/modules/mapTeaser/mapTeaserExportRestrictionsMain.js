
function mapTeaserExportRestrictionsMain(country) {

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

  var countryName = lang_countries[country.toLowerCase()];

  var tableLineCanvas = [];
  var tooltipData = [];
  var tableLines;

  tableLineCanvas[0] = "<tr><td title='" + DataforMap[0]['definition'] + "'>" + DataforMap[0]['title'] + ":</td><td class='tooltipData'>";
  tooltipData[0] = labels2[lang][DataforMap[0]['values'][country][1]];
  tableLines = tableLineCanvas[0]+tooltipData[0];

  return '' +
    teaserTitleStart +
    countryName +
    teaserTitleEnd +
    teaserTableStart +
    tableLines +
    teaserTableEnd +
    countryProfile + '';
}


