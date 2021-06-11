


function getChartWithLabels(d,lang_countries) {
	var newData = {}
	newData['options'] = d.options;
	newData['title'] = d.title;
	newData['definition'] = d.definition;
	newData['data'] = {}
	newData.data['dimensions'] = d.data.dimensions;
	newData.data['data'] = d.data.data;
	newData.data['keys'] = [];
	newData.data['keys'][0] = [];
	newData.data['keys'][1] = [];
	$.each(d.data.keys[0], function(key,val){
		if (val.toLowerCase() in lang_countries) {
			newData.data['keys'][0][key] = lang_countries[val.toLowerCase()];
		} else {
			newData.data['keys'][0][key] = val;
		}
	});
	$.each(d.data.keys[1], function(key,val){
		if (val.toLowerCase() in lang_countries) {
			newData.data['keys'][1][key] = lang_countries[val.toLowerCase()];
		} else {
			newData.data['keys'][1][key] = val;
		}
	});
	return newData;
}

