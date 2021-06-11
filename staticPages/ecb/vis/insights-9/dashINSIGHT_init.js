window.getScripts = function (scripts) {
	var xhrs = scripts.map(function (url) {
			return $.ajax({
				url: url,
				dataType: 'text'
			});
		});
	return $.when.apply($, xhrs).done(function () {
		if (xhrs.length == 1)
			arguments = [arguments];
		Array.prototype.forEach.call(arguments, function (res) {
			eval.call(this, res[0]);
		});
	});
};
window.ConvertDataECBToEpoch = function (strDate) {
	switch (strDate.length) {
	case 4: //YYYY
		return new Date(parseInt(strDate), 1, 0).getTime();
		break;
	}
}
window.GetChartDataFromJSONECBForCountry = function (JSONECB, strCountryCode, intCountryDimensionPosition) {
	intCountryIdx = JSONECB["structure"]["dimensions"]["series"][1]["values"].map(function (elem) {
			return elem.id;
		}).indexOf(strCountryCode);
	arrDates = $.map(JSONECB["structure"]["dimensions"]["observation"][0]["values"], function (elem) {
			return ConvertDataECBToEpoch(elem.id)
		});
	var array = $.map(JSONECB["dataSets"][0]["series"], function (elem, idx) {
			if (idx.split(":")[intCountryDimensionPosition] === "" + intCountryIdx) {
				arrData = $.map(elem["observations"], function (elem, idx) {
						return [[arrDates[idx], elem[0]]]
					});
			}
		});
	var arrData;
	return {
		name: strCountryCode,
		data: arrData,
		visible: false
	};
}
window.GetDataFromURL = function (inURL) {
	var def = $.Deferred();
	$.ajax({
		type: "GET",
		url: inURL + "?startPeriod=2008",
		dataType: "json",
		success: function (data) {
			def.resolve(data)
		},
		fail: function () {
			console.log("Some problem occures!");
		}
	})
	return def.promise();
};

window.GenerateSimpleChart = function (inContainer, inOptions, inChartLibrary) {
	$('#' + inContainer).highcharts(inOptions);
};

window.GenerateCheckBoxes = function (inContainer, inArrCountries) {
	inArrCountries = [["BE","Belgium"],["DE","Germany"],["EE","Estonia"],["IE","Ireland"],["GR","Greece"],["ES","Spain"],["FR","France"],["IT","Italy"],["CY","Cyprus"],["LV","Latvia"],["LT","Lithuania"],["LU","Luxembourg"],["MT","Malta"],["NL","Netherlands"],["AT","Austria"],["PT","Portugal"],["SI","Slovenia"],["SK","Slovakia"],["FI","Finland"],["U2","Euro area"]]
	$.each(inArrCountries, function (i, item) {
		$("#" + inContainer).append("<input type='checkbox' id='" + inContainer + item[0] + "' value='" + item[0] + "' /><label class='checkboxlabel' for='" + inContainer + item[0] + "'><span class='fakeLegend' id='span" + inContainer + item[0] + "'>" + item[1] + "</span></label>");
	})
};

window.AddChangeListener = function (inContainer, inCallback) {
	$('#' + inContainer + ' input[type=checkbox]').change(function () {
		if ($(this).is(":checked")) {
			varOperation = "+";
		} else {
			varOperation = "-";
		}
		var arrSelected = [];
		$('#' + inContainer + ' input:checked').each(function () {
			arrSelected.push($(this).val());
		});
		localStorage.setItem("LSSelectedCountries", JSON.stringify(arrSelected));
		inCallback(null, $(this).val(), varOperation, inContainer);
	});
};

window.InitializeCheckBoxes = function (inContainer, inArrCountries) {
	$.each(inArrCountries, function (i, val) {
		$('#' + inContainer + ' input[type=checkbox][value=' + val + ']').prop("checked", "true").change();
	})
}

window.ProcessRequestSpider = function (inArrAllCountries, varLastCountry, varOperation, varContainer) {
	//if ($("#CheckboxesPaymentServices input:checked").length == 0) return;
	$.each(arrYearsToCompare, function (idx, elem) {
		//var chart = ;
		switch (varOperation) {
		case "-":
			$('head').append("<style>#span" + varContainer + varLastCountry + ":before{background: #ddd;}</style>")
			intSerieIdx = ($('#PaymentServices' + elem).highcharts().series).map(function (elem) {
				return elem.name;
			}).indexOf(varLastCountry.toUpperCase());
			$('#PaymentServices' + elem).highcharts().series[intSerieIdx].hide();
			break;
		case "+":
			// before initialize arrSerieToColor is undefined
			if (typeof($.arrSerieToColor) != "undefined") {
				$('head').append("<style>#span" + varContainer + varLastCountry + ":before{background: " + $.arrSerieToColor[varLastCountry] + ";}</style>")
				intSerieIdx = ($('#PaymentServices' + elem).highcharts().series).map(function (elem) {
					return elem.name;
				}).indexOf(varLastCountry.toUpperCase());
				$('#PaymentServices' + elem).highcharts().series[intSerieIdx].show();
			} else {
				var arrTemp = [];
				for (i = 0; i < 6; i++) {
					idxYear = ($.map($.JSONECB[i]['structure']['dimensions']['observation'][0]['values'], function (obj, index) {
							if (obj.id == elem) {
								return index;
							}
						})[0]);
					idxCountry = ($.map($.JSONECB[i]['structure']['dimensions']['series'][1]['values'], function (obj, index) {
							if (obj.id == varLastCountry.toUpperCase()) {
								return index;
							}
						})[0]);
					countryKey = Object.keys($.JSONECB[i]['dataSets'][0]['series'])[idxCountry];
					if (typeof($.JSONECB[i]['dataSets'][0]['series'][countryKey]['observations'][idxYear]) != "undefined") {
						arrTemp.push($.JSONECB[i]['dataSets'][0]['series'][countryKey]['observations'][idxYear][0]);
					} else {
						arrTemp.push(null);
					}
				}
				$('#PaymentServices' + elem).highcharts().addSeries({
					name: varLastCountry,
					data: arrTemp,
					pointPlacement: 'on',
					visible: false
				})
			}
			break;
		}
	})
}

window.ProcessRequestLines = function (inArrAllCountries, varLastCountry, varOperation, varContainer) {
	switch (varOperation) {
		case "-":
                        $('#NumberOfCardPayments, #AverageValueOfCardPayments').each(function (idx) {
			  var series = $(this).highcharts().series;
			  if (!idx) {
                            $('head').append("<style>#span" + varContainer + varLastCountry + ":before{background: #ddd;}</style>");
			    intSerieIdx = series.map(function (elem) {
				return elem.name;
			     }).indexOf(varLastCountry.toUpperCase());
                          }
                          series[intSerieIdx].hide();
                        });
			break;
		case "+":
                        $('#NumberOfCardPayments, #AverageValueOfCardPayments').each(function (idx) {
			  var series = $(this).highcharts().series;
                          if (!idx) {
			    intSerieIdx = series.map(function (elem) {
				return elem.name;
			    }).indexOf(varLastCountry.toUpperCase());
			    $('head').append("<style>#span" + varContainer + varLastCountry + ":before{background: " + series[intSerieIdx].color + ";}</style>")
                          }
			  series[intSerieIdx].show();
                        });
			break;
	}

}

window.HideTooltipsWhenLeave = function (arrContainers) {
	$(arrContainers.map(function (elem) {
			return "#" + elem
		}).join(",")).mouseleave(function (e) {
		arrContainers.forEach(function (elem) {
			if (typeof $("#" + elem).highcharts() != 'undefined')
				$("#" + elem).highcharts().tooltip.hide()
		});
	});
}
window.getSharedTooltipSimple = function (varDateFormat, varDecimals, varSuffix) {
	return {
		shared: true,
		formatter: function () {
			return '<span style="color:#000; font-size:0.8em;">' + Highcharts.dateFormat(varDateFormat, this.x) + '</span><br/>' + this.points.map(function (point, idx) {
				return '<span style="color:' + point.series.color + '">\u25CF</span><span> ' + (point.series.legendItem.textStr) + '</span>: <span style="color:#000;font-weight:bold">' + Highcharts.numberFormat(point.y, varDecimals) + ' ' + varSuffix + '</span>';
			}).join('<br/>');
		}
	}
};
$(function () {
	var scripts = [
		'../../resources/highcharts-release/highcharts.js',
		'../../resources/highcharts-release/highcharts-more.js',
	];
	$.when(
		getScripts(scripts)).done(function () {
		Highcharts.setOptions({
			colors: ['#e6194b', '#3cb44b', '#ffe119', '#000075', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#808080', '#4363d8', '#ffffff', '#000000']
		});
		$.arrSites = [{
				title: "Share of all payments services - Share of card payments",
				div: "ShareOfCardPayments",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F000.I1A.Z00Z.NP.X0.20.Z0Z.Z",
				chartLibrary: "Chart",
				chartType: "ommit"
			}, {
				title: "Share of all payments services - Share of credit transfers",
				div: "ShareOfCreditTransfers",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F000.I31.Z00Z.NP.X0.20.Z0Z.Z",
				chartLibrary: "Chart",
				chartType: "ommit"
			}, {
				title: "Share of all payments services - Share of direct debits",
				div: "ShareOfDirectDebts",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F000.I34.Z00Z.NP.X0.20.Z0Z.Z",
				chartLibrary: "Chart",
				chartType: "ommit"
			}, {
				title: "Share of all payments services - Share of cheques",
				div: "ShareOfCheques",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F000.I35.Z00Z.NP.X0.20.Z0Z.Z",
				chartLibrary: "Chart",
				chartType: "ommit"
			}, {
				title: "Share of all payments services - Share of e-money",
				div: "ShareOfEmoney",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F000.IEM.Z00Z.NP.X0.20.Z0Z.Z",
				chartLibrary: "Chart",
				chartType: "ommit"
			}, {
				title: "Share of all payments services - Share of other",
				div: "ShareOfOther",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F000.I37.Z00Z.NP.X0.20.Z0Z.Z",
				chartLibrary: "Chart",
				chartType: "ommit"
			}, {
				title: "Number of card payments",
				div: "NumberOfCardPayments",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F000.I1A.Z00Z.NC.X0.20.Z0Z.Z",
				chartLibrary: "Chart",
				chartType: "line",
				postfix: "",
				legendEnabled: false
			}, {
				title: "Average value of card payments",
				div: "AverageValueOfCardPayments",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F000.I1A.Z00Z.VA.X0.20.Z01.E",
				chartLibrary: "Chart",
				chartType: "line",
				postfix: "EUR",
				legendEnabled: false
			}, {
				title: "Value of ATM cash withdrawals per card",
				div: "ValueOfATMCashWithdrawalsPerCard",
				code: "PSS/A.AT+BE+CY+DE+EE+ES+FI+FR+GR+IE+IT+LT+LU+LV+MT+NL+PT+SI+SK+U2.F100.I10.I111.VD.X0.20.Z01.E",
				chartLibrary: "Chart",
				chartType: "line",
				postfix: "EUR",
				legendEnabled: true
			} // */
		];
		arrYearsToCompare = [2008, 2018];
		strDivBody = "";
		$.arrParameters = [];
		$.arrParameters['INSIGHT'] = [];
		$.Countries = [];
		$.each($.arrSites, function (idx, elem) {
			switch (elem['chartType']) {
			case "ommit":
				$.arrParameters['INSIGHT'].push({
					URICode: elem['code'] + "",
					options: {
						chart: {
							type: elem['chartType']
						}
					}
				});
				$.Countries.push(["BE","DE","EE","IE","GR","ES","FR","IT","CY","LT","LV","LU","MT","NL","AT","PT","SI","SK","FI","U2"]);
				break;
			default:
				$.arrParameters['INSIGHT'].push({
					container: elem['div'],
					URICode: elem['code'] + "",
					chartLibrary: elem['chartLibrary'],
					options: {
						chart: {
							type: elem['chartType']
						},
						plotOptions: {
							line: {
								marker: {
									enabled: false
								}
							},
							series: {
								events: {
									legendItemClick: function (e) {
										var visibleSeries = function () {
											var counter = 0;
											$.each(e.target.chart.series, function (i, v) {
												if (v.visible) {
													counter++;
												}
											});
											return counter;
										}
										if (visibleSeries() <= 1 && this.visible) {
											return false;
										} else {
											return true;
										}
									}
								},
								showInNavigator: true
							}
						},
						legend: {
							labelFormatter: function () {
								return ($.arrCountryCodeToCountryName[this.name]);
							},
							enabled: elem['legendEnabled']
						},

						startOnTick: true,
						xAxis: {
							type: 'datetime',
							labels: {
								formatter: function () {
									return Highcharts.dateFormat("%Y", this.value);
								}
							},
							tickInterval: 31536000000
						},
						credits: {
							text: "ECB"
						},
						title: {
							text: null
						},
						tooltip: {
							shared: true,
							formatter: function () {
								return '<span style="color:#000; font-size:0.8em;">' + Highcharts.dateFormat('%Y', this.x) + '</span><br/>' + this.points.map(function (point, idx) {
									return '<span style="color:' + point.series.color + '">\u25CF</span><span> ' + $.arrCountryCodeToCountryName[point.series.name] + '</span>: <span style="color:#000;font-weight:bold">' + Highcharts.numberFormat(point.y, 1) + ' ' + elem['postfix'] + '</span>';
								}).join('<br/>');
							}
						},
						rangeSelector: {
							enabled: false
						},
						yAxis: {
							title: {
								text: null
							},
							min: 0
						},
						credits: {
							enabled: false
						}
					},
					checkBoxCont: "Checkboxes" + elem['div']
				});
				$.Countries.push(["BE","DE","EE","IE","GR","ES","FR","IT","CY","LT","LV","LU","MT","NL","AT","PT","SI","SK","FI","U2"]);
				break;
			}
		});

		$("chart1").replaceWith("<div id='PaymentServices" + arrYearsToCompare[0] + "' style='height: 320px; width: 350px; float: left;'></div><div id='PaymentServices" + arrYearsToCompare[1] + "' style='height: 320px; width: 350px; float: left;'></div>");
		$("checkbox1").replaceWith("<div class='CSSCheckbox CSSCheckboxSQUARE' id='CheckboxesPaymentServices' style='height: 100px; width: 700px; margin-top: 10px; float: left;'></div>");
		$("chart2").replaceWith("<div id='NumberOfCardPayments' style='height: 400px; width: 700px; float: left;'></div>");
		$("chart3").replaceWith("<div id='AverageValueOfCardPayments' style='height: 400px; width: 700px; float: left;'></div>");
		$("checkbox2").replaceWith("<div class='CSSCheckbox CSSCheckboxLINE' id='CheckboxesLineCharts' style='height: 100px; width: 700px; margin-top: 10px; float: left;'></div>");
		$("chart4").replaceWith("<div id='ValueOfATMCashWithdrawalsPerCard' style='height: 400px; width: 700px; float: left;'></div>");

		$.JSONECB;
		var deferreds = [];
		$.each($.arrParameters['INSIGHT'], function (index, value) {
			deferreds.push(GetDataFromURL(value.URICode+'.json'));
			if (value.options.chart.type != "ommit") {
				var chart = GenerateSimpleChart(value.container, value.options);
				//InjectOption(value.container, value.options);
			};
		})
		$.when.apply($, deferreds).done(function () {
			$.JSONECB = arguments;
			HideTooltipsWhenLeave($.map($.arrParameters['INSIGHT'], function (value, index) {
					return [value.container]
				}));
			$.each(arrYearsToCompare, function (idx, elem) {
				$('#' + 'PaymentServices' + elem).highcharts({
					chart: {
						polar: true,
						type: 'area',
						spacingLeft: 60,
						spacingRight: 60
					},
					credits: {
						enabled: false
					},
					legend: {
						enabled: false
					},
					pane: {
						size: '100%'
					},
					plotOptions: {
						series: {
							fillOpacity: 0.1
						}
					},
					series: [],
					title: {
						text: null
						/*text: elem*/
					},
					tooltip: {
						shared: true,
						formatter: function () {
							return '<span style="color:#000; font-size:0.8em;">' + this.x + '</span><br/>' + this.points.map(function (point, idx) {
								return '<span style="color:' + point.series.color + '">\u25CF</span><span> ' + $.arrCountryCodeToCountryName[point.series.name] + '</span>: <span style="color:#000;font-weight:bold">' + Highcharts.numberFormat(point.y, 1) + ' %' + '</span>';
							}).join('<br/>');
						}
						//pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.1f}%</b><br/>'
					},
					xAxis: {
						categories: ['Card payments', 'Credit transfers', 'Direct debits', 'Cheques', 'E-money', 'Other'],
						tickmarkPlacement: 'on',
						lineWidth: 0
					},
					yAxis: {
						labels: {
							enabled: false
						},
						gridLineInterpolation: 'polygon',
						lineWidth: 0,
						min: 0,
						max: 100
					}
				});
			});

			// Initialization Spider Checkboxes
			$.arrCountryCodeToCountryName = [];
			GenerateCheckBoxes("CheckboxesPaymentServices", $.JSONECB[0]["structure"]["dimensions"]["series"][1]["values"].map(function (elem) {
					$.arrCountryCodeToCountryName[elem.id] = elem.name.replace(" (changing composition)", "");
					return [elem.id, elem.name.replace(" (changing composition)", "")]
				}));
			AddChangeListener("CheckboxesPaymentServices", ProcessRequestSpider);
			InitializeCheckBoxes("CheckboxesPaymentServices", $.Countries[0]);

			$.arrSerieToColor = [];

			$('#PaymentServices' + arrYearsToCompare[0]).each(function () {
                           $.each($(this).highcharts().series, function (j, serie) {
				$.arrSerieToColor[serie.name] = serie.color;
				//$('head').append("<style>#span" + serie.name + ":before{background: " + serie.color + ";}</style>")
                           });
			});

			$('#CheckboxesPaymentServices input').each(function () { //iterate all listed checkbox items
				this.checked = false;
			});

			$('#PaymentServices' + arrYearsToCompare[0]).each(function () {
                          $.each($(this).highcharts().series, function (j, serie) {
				$('head').append("<style>.fakeLegend:before{background:#ddd;}</style>")
                           });
			});

			// Spider charts
			arrCountries = ["PT", "DE", "SK", "LU", "MT", "U2"];
			$.each(arrCountries, function (idx, value) {
				$('#CheckboxesPaymentServices' + value).trigger('click');
			})

			// Data for charts 6 - 8
			for (i = 6; i < 9; i++) {
				$.each($.Countries[i], function (idx, value) {
					$('#' + $.arrSites[i]['div']).each(function () {
                                          $(this).highcharts().addSeries(GetChartDataFromJSONECBForCountry($.JSONECB[i], value.toUpperCase(), 1));
                                        });
				});
			};

			// Initialization charts 6, 7
			$.arrCountryCodeToCountryName = [];
			GenerateCheckBoxes("CheckboxesLineCharts", $.JSONECB[0]["structure"]["dimensions"]["series"][1]["values"].map(function (elem) {
					$.arrCountryCodeToCountryName[elem.id] = elem.name.replace(" (changing composition)", "");
					return [elem.id, elem.name.replace(" (changing composition)", "")]
				}));

			
			InitializeCheckBoxes("CheckboxesLineCharts", $.Countries[0]);
			$('#CheckboxesLineCharts input').each(function () { //iterate all listed checkbox items
				this.checked = false;
			});

			arrCountries = ["DE", "IT", "LU", "U2"];
			AddChangeListener("CheckboxesLineCharts", ProcessRequestLines);
			$.each(arrCountries, function (idx, value) {
				$('#CheckboxesLineCharts'+value).trigger('click');
			});			
			
			

			//"8 - Value of ATM cash withdrawals per card",
			arrCountries = ["AT", "LU", "U2"];
			intIdx = 8;
			$('#' + $.arrSites[intIdx]['div']).each(function () {
                          var series = $(this).highcharts().series;
			  $.each(arrCountries, function (idx, value) {
				intSerieIdx = series.map(function (elem) {
					return elem.name;
				}).indexOf(value);
				series[intSerieIdx].show();
                          });
			});

			$("#CheckboxesPaymentServices input:checkbox").click(function () {
				if ($("#CheckboxesPaymentServices input:checkbox:checked").length == 1) {
					$(':checked').prop('disabled', true);
				} else {
					$(':checked').prop('disabled', false);
				}
			});

			$("#CheckboxesLineCharts input:checkbox").click(function () {
				if ($("#CheckboxesLineCharts input:checkbox:checked").length == 1) {
					$(':checked').prop('disabled', true);
				} else {
					$(':checked').prop('disabled', false);
				}
			});

		});
	});
});
