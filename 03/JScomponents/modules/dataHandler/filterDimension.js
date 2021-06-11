
//filters a multidimensional data object on the first dimension

function filterDimension(d,filter) {

  filter = filter.toString().toUpperCase();
  var filterKeys = d.keys[0];
	var newData = {};
	newData['dimensions'] = d.dimensions.slice(1);
	newData['keys'] = d.keys.slice(1);

  var filtered = filterKeys.indexOf(filter);
	newData['data'] = d.data[filtered];

	return newData;
}
