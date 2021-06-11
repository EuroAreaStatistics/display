


function filterYear(d,filter) {
	var newData = {}
	newData['title'] = d.title
	newData['definition'] = d.definition
	newData['options'] = d.options
	newData['data'] = {}
	newData.data['dimensions'] = d.data.dimensions;
	newData.data['keys'] = [];
	newData.data['keys'][0] = d.data.keys[0];
	newData.data['keys'][1] = [];
	newData.data['data'] = [];
	var idxYear = []
	$.each(filter, function (key,value) {
		idxYear.push(d.data.keys[1].indexOf(value))
		newData.data.keys[1].push(value);
	});
	$.each(newData.data.keys[0], function(key,cr){
		newData.data.data[key] = [];
		$.each(idxYear, function (yKey,value) {
			newData.data.data[key].push(d.data.data[key][value])
		});
	});
	return newData;
}
