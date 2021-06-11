

//returns a table with title for a map wizard layer

//Dependencies
//dataHandler/getTableData.js

function addComplexTable (values,country) {

  var tab = window.wizardConfig.project.tabs[window.page];
  var commodityLabels = window.wizardConfig.tabs[tab].labels;

  var purposeCodes = window.wizardConfig.tabs[tab].purposeCodes[country.toUpperCase()];

	var labels1 = {
		1:'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="12" height="12"><circle fill="#a80606" cx="6" cy="6" r="6" stroke="white" stroke-width="0" /></svg>',
		2:'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="12" height="12"><circle fill="#e70a0d" cx="6" cy="6" r="6" stroke="white" stroke-width="0" /></svg>',
		3:'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="12" height="12"><circle fill="#e78b20" cx="6" cy="6" r="6" stroke="white" stroke-width="0" /></svg>',
		4:'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="12" height="12"><circle fill="#d3e729" cx="6" cy="6" r="6" stroke="white" stroke-width="0" /></svg>',
		5:'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="12" height="12"><circle fill="#ca29e7" cx="6" cy="6" r="6" stroke="white" stroke-width="0" /></svg>',
		6:'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="12" height="12"><circle fill="#1ee723" cx="6" cy="6" r="6" stroke="white" stroke-width="0" /></svg>',
	};

  var labels2 = {};

  labels2['en'] = {
		1:'Export prohibition',
		2:'Export quota',
		3:'Export tax',
		4:'Non-automatic licencing',
		5:'Other measures',
		6:'No restrictions',
	};

  labels2['cn'] = {
      1:'禁止出口',
      2:'出口配额',
      3:'出口税',
      4:'非自动许可',
      5:'其他措施',
      6:'无限制',
  };

  var labels3 = {};

  labels3['en'] = {
    'A':	'conservation of natural resources',
    'B':	'control of foreign exchange',
    'C':	'generate revenue',
    'D':	'health or environmental protection',
    'E':	'monitor or control export activity',
    'F':	'promote further processing/value added',
    'G':	'protect local downstream industry',
    'H':	'safeguard domestic supply',
    'I':	'other',
    'J':	'no purpose found/indicated',
  };

  labels3['cn'] = {
      'A':	'自然资源保护',
      'B':	'外汇管制',
      'C':	'创收',
      'D':	'健康或环境保护',
      'E':	'出口活动监管',
      'F':	'推动进一步处理/增值',
      'G':	'保护本地下游产业',
      'H':	'保障国内供应',
      'I':	'其他',
      'J':	'未发现/说明目的',
  };

  var labels4 = {
    'en' : 'Commodity',
    'cn' : '商品',
  }

  var labels5 = {
    'en' : 'purpose',
    'cn' : '目的',
  }


	var head = '<table class="complexTable tablesorter"><thead><tr>'+
              '<th><span>'+labels4[lang]+'<span></th>'+
              '<th><span>'+values[0].title+'<span></th>'+
              '<th><span>'+values[1].title+'<span></th>'+
              '<th><span>'+values[2].title+'<span></th></tr></thead><tbody>';
	var foot = '</tbody></table>';
	var rows = '';
  var data = [];

  $.each(values, function(chart,content){
    data[chart]=getTableData (content.data)
  });
	$.each(data[0], function(indicator,v){
    var comLabel = [];
    if (commodityLabels.labels[indicator]) {
      comLabel[0] = commodityLabels.labels[indicator].name;
      comLabel[1] = commodityLabels.labels[indicator].definition;
    } else {
      comLabel[0] = indicator;
      comLabel[1] = '--';
    }

    var restrictExplain = labels2[lang][v[country.toUpperCase()]];

    var purpCode;
    var purpCodeExplain = [];
    if (purposeCodes[indicator]) {
      var purpCodeList = purposeCodes[indicator].split("");
      purpCode = ' ('+purpCodeList+')';
      $.each(purpCodeList, function(keys,codeValue){
        purpCodeExplain.push(labels3[lang][codeValue]);
      });
      restrictExplain = restrictExplain+' - '+labels5[lang]+': '+purpCodeExplain;
    } else {
      purpCode = '';
    }

    var valRow1;
    var valRow2;
    var labelRow1;
    var labelRow2;
    if (data[1][indicator]&& data[1][indicator][country.toUpperCase()]) {
      valRow1 = data[1][indicator][country.toUpperCase()];
      labelRow1 = valRow1;
    } else {
      valRow1 = 0;
      labelRow1 = '--';
    }
    if (data[2][indicator] && data[2][indicator][country.toUpperCase()] ) {
      valRow2 = data[2][indicator][country.toUpperCase()];
      labelRow2 = valRow2;
    } else {
      valRow2 = 0;
      labelRow2 = '--';
    }

		if (v[country.toUpperCase()] != null) {
				rows = rows.concat('<tr><td title="'+comLabel[1]+'">'+comLabel[0]+'</td>'+
                            '<td title="'+restrictExplain+'">'+labels1[v[country.toUpperCase()]]+purpCode+'</td>'+
                            '<td>'+
                              '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="'+(valRow1+40)+'" height="13">'+
                                 '<rect width="'+valRow1+'" height="13" fill="#a2b9f0" y="0" x="0" stroke="white" stroke-width="0" />'+
                                 '<text text-anchor="start" x="'+(valRow1+4)+'" y="11"  font-family="Verdana" font-size="11" color="#636363">'+labelRow1+'</text>'+
                               '</svg>'+
                            '</td>'+
                            '<td>'+
                              '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="'+(valRow2+40)+'" height="13">'+
                                 '<rect width="'+valRow2+'" height="13" fill="#a2b9f0" y="0" x="0" stroke="white" stroke-width="0" />'+
                                 '<text text-anchor="start" x="'+(valRow2+4)+'" y="11"  font-family="Verdana" font-size="11" color="#636363">'+labelRow2+'</text>'+
                               '</svg>'+
                            '</td>'+
                           '</tr>'
                           )
		}
	});
	return head+rows+foot
}
