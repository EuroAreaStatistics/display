window.getScripts = function(scripts) {
	var xhrs = scripts.map(function(url) {
		return $.ajax({
			url: url,
			dataType: 'text'
		});
	});
	return $.when.apply($, xhrs).done(function() {
		if (xhrs.length == 1) arguments = [arguments];
		Array.prototype.forEach.call(arguments, function(res) {
			eval.call(this,res[0]);
		});
	});
};

window.ConvertDataECBToEpoch = function(strDate) {
	switch(strDate.length) {
		case 7:
			switch(strDate.charAt(5)) {
				case "Q":
					return new Date(parseInt(strDate.substr(0, 4)), parseInt(strDate.substr(6, 1))*3, 0).getTime();
					break;
				default:
					return new Date(parseInt(strDate.substr(0, 4)), parseInt(strDate.substr(5, 2)), 0).getTime();
					break;
			}
		break;
	}
}

window.GetChartDataFromJSONECBForCountry = function(JSONECB,strCountryCode,intCountryDimensionPosition) {
	var arrData;
	intCountryIdx = JSONECB["structure"]["dimensions"]["series"][1]["values"].map(function(elem) {return elem.id; }).indexOf(strCountryCode);
	arrDates = $.map(JSONECB["structure"]["dimensions"]["observation"][0]["values"], function(elem) {return ConvertDataECBToEpoch(elem.id)});
	var array = $.map(JSONECB["dataSets"][0]["series"], function(elem,idx) {
		if (idx.split(":")[intCountryDimensionPosition] === ""+intCountryIdx) {
			arrData = $.map(elem["observations"], function(elem, idx) {return [[arrDates[idx],elem[0]]]});
		}
	});
	return {name: strCountryCode, data: arrData};
}

window.GetDataFromURL = function(inURL) {
	var def = $.Deferred();
	$.ajax({
		type: "GET",
		url: inURL,
		dataType: "json",
		success: function(data) {
			def.resolve(data)
		},
		fail: function() {
			console.log("Some problem occures!");
		}
	})
	return def.promise();
};

window.GenerateSimpleChart = function(inContainer,seriesOptions) {
	var options = {
		chart: {
			renderTo: inContainer
		},
		series : seriesOptions
	};
	$('#'+inContainer).highcharts(options);

};

window.GenerateCheckBoxes = function(inContainer, inArrCountries) {
        // sort countries alphabetically with Euro area last
        inArrCountries.sort(function (a,b) {
          if (a[1] === b[1]) return 0;
          if (a[0] === 'U2') return 1;
          if (b[0] === 'U2') return -1;
          if (a[1] < b[1]) return -1;
          return 1;
        });
        // transpose buttons so that countries are sorted downwards
	var rows = 4, cols = inArrCountries.length / rows, buttons = [], i, j;
        for (i = 0; i < rows; i++) {
          for (j = 0; j < cols; j++) {
            k = i + j*rows;
            if (k < inArrCountries.length) {
              buttons.push(inArrCountries[k]);
            } else {
              buttons.push(null);
            }
          }
        }
	$.each(buttons, function(i,item) {
          if (item === null) {
            $("#"+inContainer).append("<input type='checkbox' id='" + inContainer + i + "' value='" + i + "' /><label class='checkboxlabel' for='" + inContainer + i + "' style='visibility: hidden'>" + i + "</label>");
          } else {
            $("#"+inContainer).append("<input type='checkbox' id='" + inContainer + item[0] + "' value='" + item[0] + "' /><label class='checkboxlabel' for='" + inContainer + item[0] + "'>" + item[1] + "</label>");
          }
        });
};

window.AddChangeListener = function(inContainer,inCallback) {
	$('#'+inContainer+' input[type=checkbox]').change(function() {
		if($(this).is(":checked")) {
			varOperation = "+";
		} else {
			varOperation = "-";
		}
		var arrSelected = [];
		$('#'+inContainer+' input:checked').each(function() {
			arrSelected.push($(this).val());
		});
		localStorage.setItem("LSSelectedCountries",JSON.stringify(arrSelected));
		inCallback(null,$(this).val(),varOperation,inContainer);
	});
};

window.InitializeCheckBoxes = function(inContainer, inArrCountries) {
	$.each(inArrCountries, function (i, val) {
		$('#'+inContainer+' input[type=checkbox][value='+val+']').prop("checked","true").change();
	})
}

window.ProcessRequest = function(inArrAllCountries, varLastCountry, varOperation, varContainer) {
	intContIdx = $.map($.arrParameters['INSIGHT'], function (value, index) {return [value.checkBoxCont]}).indexOf(varContainer);
	var chartContainer = $.arrParameters['INSIGHT'][intContIdx]["container"];
	var chart = $('#'+chartContainer).highcharts();
	switch(varOperation) {
		case "-":
			intSerieIdx = (chart.series).map(function(elem) {return elem.name; }).indexOf(varLastCountry.toUpperCase());
			chart.series[intSerieIdx].remove();
			break;
		case "0":
			StockService.removeAllSeries(chartContainer);
			chart.colorCounter = 0;
			chart.symbolCounter = 0;
			break;
		case "+":
			chart.addSeries(GetChartDataFromJSONECBForCountry($.JSONECB[intContIdx],varLastCountry.toUpperCase(),1));
			break;
		case "1":
			StockService.removeAllSeries(chartContainer);
			chart.addSeries(GetChartDataFromJSONECBForCountry($.JSONECB[intContIdx],varLastCountry.toUpperCase(),1));
			break;
	}
}

window.HideTooltipsWhenLeave = function(arrContainers) {
	$(arrContainers.map(function(elem) {return "#"+elem}).join(",")).mouseleave(function(e){
		arrContainers.forEach(function(elem) {if (typeof $("#"+elem).highcharts() != 'undefined') $("#"+elem).highcharts().tooltip.hide()});
	});
}

window.getSharedTooltipSimple = function(varDateFormat,varDecimals, varSuffix) {
	return {shared : true,formatter: function() {return '<span style="color:#000; font-size:0.8em;">' + Highcharts.dateFormat(varDateFormat,this.x) + '</span><br/>' +this.points.map(function(point, idx) {return '<span style="color:' + point.series.color + '">\u25CF</span><span> ' + (point.series.name==="U2"?"Euro Area":point.series.name) +'</span>: <span style="color:#000;font-weight:bold">' +Highcharts.numberFormat(point.y,varDecimals) + ' ' + varSuffix + '</span>';}).join('<br/>');}}
};

window.getSharedTooltipSimpleQ = function(varDecimals, varSuffix) {
	return {shared : true,formatter: function() {
	return '<span style="color:#000; font-size:0.8em;">' + (''+Highcharts.dateFormat('%m',this.x)/3).replace("1", "I").replace("2", "II").replace("3", "III").replace("4", "IV") +  ' Quarter ' + Highcharts.dateFormat("%Y",this.x) + '</span><br/>' +this.points.map(function(point, idx) {return '<span style="color:' + point.series.color + '">\u25CF</span><span> ' + (point.series.name==="U2"?"Euro Area":point.series.name) +'</span>: <span style="color:#000;font-weight:bold">' +Highcharts.numberFormat(point.y,varDecimals) + ' ' + varSuffix + '</span>';}).join('<br/>');}
	};
}

window.InjectOption = function(inContainer,inOptions) {
	var inOrgOptions = $("#"+inContainer).highcharts().options;
	$.extend(true, inOrgOptions, inOptions);
	$('#'+inContainer).highcharts(inOrgOptions);
};

$(function () {
	var scripts = [
		'../../resources/highcharts-release/highcharts.js'
	];
	$.when(
		getScripts(scripts)
	).done(function() {
		$("section").replaceWith(
				"<div id='containterCheckboxesCorporatesTotal' style='height: 100px; margin-top: 10px; float: left;'></div>" +
				"<div id='containerCorporatesTotal' style='height: 400px; width: 100%; float: left;'></div>" +
				"<div id='containterCheckboxesLoanToDepositRatio' style='height: 100px; margin-top: 10px; float: left;'></div>" +
				"<div id='containerLoanToDepositRatio' style='height: 400px; width: 100%; float: left;'></div>"
		);
		$.arrParameters = [];
		$.arrParameters['INSIGHT'] = [{container:"containerCorporatesTotal", URICode: "data1",



		options: {
			plotOptions: {line: {marker: {enabled: false}}},
			legend: {
				labelFormatter: function () {
					return (this.name==="U2"?"Euro Area":this.name);
				}
			},
			startOnTick: true,
			xAxis: {type: 'datetime',labels:{formatter: function () {return Highcharts.dateFormat("%Y", this.value);}},tickInterval: 31536000000},
			credits: {enabled: false},
			title: {margin: 5, align: 'left', style: {fontWeight: 'bold' },
			text: "Growth rates of bank loans to corporates"},
			tooltip: getSharedTooltipSimple('%B %Y',2,"%"),
			rangeSelector: {enabled: false},
			yAxis: {title: {text: "Percent"}}
		}
		,checkBoxCont: "containterCheckboxesCorporatesTotal"},{container:"containerLoanToDepositRatio", URICode: "data2",

		options: {
			plotOptions: {line: {marker: {enabled: false}}},
			legend: {
				labelFormatter: function () {
					return (this.name==="U2"?"Euro Area":this.name);
				}
			},
			yAxis: {title: {text: "Percent"}},
			xAxis: {startOnTick: true, type: 'datetime',labels:{formatter: function () {return Highcharts.dateFormat("%Y", this.value);}},tickInterval: 31536000000},credits: {text: "Source: ECB Statistics", href: "http://sdw.ecb.europa.eu/"},title: {margin: 5, align: 'left', style: {fontWeight: 'bold' },text: "Loan-to-deposit ratio"},tooltip: getSharedTooltipSimpleQ(2,'%'),rangeSelector: {enabled: false}
		}

		,checkBoxCont: "containterCheckboxesLoanToDepositRatio"}];

		$.Countries = [["U2","DE","IT","FR"],["U2","EE","IT","MT"]];
		$.JSONECB;
		var deferreds = [];

		$.each($.arrParameters['INSIGHT'], function (index, value) {
			deferreds.push(GetDataFromURL(encodeURIComponent(value.URICode)+".json"));
			var chart = GenerateSimpleChart(value.container,null);
			InjectOption(value.container,value.options);
		})

		$.when.apply($, deferreds).done( function () {
			$.JSONECB = arguments;

			HideTooltipsWhenLeave($.map($.arrParameters['INSIGHT'], function (value, index) {return [value.container]}));

			$.each($.arrParameters['INSIGHT'], function (index, value) {
				GenerateCheckBoxes(value.checkBoxCont,$.JSONECB[index]["structure"]["dimensions"]["series"][1]["values"].map(function(elem) {return [elem.id,elem.name.replace(" (changing composition)","")]}));
				AddChangeListener(value.checkBoxCont,ProcessRequest);
				InitializeCheckBoxes(value.checkBoxCont,$.Countries[index]);
			});
		});
	});
});
