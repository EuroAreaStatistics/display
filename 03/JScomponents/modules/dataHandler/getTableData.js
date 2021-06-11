
//convert JSON model into table format

function getTableData (data) {
	var newData = {};
	$.each(data.keys[1], function (key,value) {
	  newData[value]={};
	  $.each(data.keys[0], function (k,v) {
	  newData[value][v] = data.data[data.keys[0].indexOf(v)][data.keys[1].indexOf(value)];
	  });
	});
	return newData;
}
