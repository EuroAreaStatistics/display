

//returns a table with title for a map wizard layer

//Dependencies
//dataHandler/getTableData.js

function addSimpleTable (values,country,labels) {
	var tableLabels = {};
	tableLabels['fishery-innovation'] = {
		0:'<span style="color:red">No</span>',
		1:'<span style="color:green">Yes</span>',
		2:'<span style="color:orange">at EU level</span>',
		3:'<span style="color:green">EU & national level</span>',
	};
	tableLabels['export-restrictions'] = {
		1:'<span style="color:#a80606">Export prohibition</span>',
		2:'<span style="color:#e70a0d">Export quota</span>',
		3:'<span style="color:#e78b20">Export tax</span>',
		4:'<span style="color:#d3e729">Non-automatic licencing</span>',
		5:'<span style="color:#ca29e7">Other measures</span>',
		6:'<span style="color:#1ee723">No restrictions</span>',
	};
	var labels1 = {
		0:'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="12" height="12"><circle cx="6" cy="6" r="6" stroke="white" stroke-width="0" fill="red" /></svg>',
		1:'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="12" height="12"><circle cx="6" cy="6" r="6" stroke="white" stroke-width="0" fill="green" /></svg>',
	};
	if (labels) {
		var selectedLabels = tableLabels[labels];
	}

	var title = '<h5 class="simpleTableTitle"><table><tr><td>'+values.title+'</table></tr></td></h5>';
	var head = '<table class="simpleTable">';
	var foot = '</table>';
	var rows = '';
	var data = getTableData (values.data)
	$.each(data, function(indicator,v){
		if (v[country.toUpperCase()] != null) {
			if (labels) {
				rows = rows.concat('<tr><td>'+indicator+'</td><td>'+selectedLabels[v[country.toUpperCase()]]+'</td></tr>')
			} else {
				rows = rows.concat('<tr><td>'+indicator+'</td><td>'+v[country.toUpperCase()]+values.options.tooltipUnit+'</td></tr>')
			}
		}
	});
	return title+head+rows+foot
}
