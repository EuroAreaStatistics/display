


function filterLocation(d,filter) {
  var parseFilter  = [];
  $.each(filter, function (k,v) {
    parseFilter.push(v.toUpperCase());
  });
	var newData = {}
	newData['title'] = d.title
	newData['definition'] = d.definition
	newData['options'] = d.options
	newData['data'] = {}
	newData.data['dimensions'] = d.data.dimensions;
	newData.data['keys'] = [];
	newData.data['keys'][0] = [];
	newData.data['keys'][1] = d.data.keys[1];
	newData.data['data'] = [];
	$.each(d.data.keys[0], function (key,value) {
		if ($.inArray(value.toUpperCase(),parseFilter)>-1) {
			newData.data.keys[0].push(value.toUpperCase());
			var a = d.data.keys[0].indexOf(value);
			newData.data.data.push(d.data.data[a]);
		}
  });
	return newData;
}
