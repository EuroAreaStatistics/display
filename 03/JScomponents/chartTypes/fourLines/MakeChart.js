
function MakeChart(datafile,container,options) {
	var self=this;
	this.datafile=datafile;
	this.options=options;
	this.container=container;
	this.goaldata=[];

//	var selectedYear = -1; // -1 for latest

	var selectedYear = -1;

	this.loadData=function() {
		if ((typeof datafile) != 'string') {
			self.parseJSONData(datafile);
		};
	};

// load data from JSON file
	this.parseJSONData=function(data) {

		var key1;
		var key2;
		var ids = [];
		// key1: first non-numeric key with more than one value
		// key2: first numeric key
		// ids: array filled with 0 for storing k,v at key1,key2
		$.each(data.keys, function(k,v) {
			if ($.isNumeric(v[0])) {
				if (key2 == null) key2 = k;
			} else {
				if (key1 == null && v.length > 1) key1 = k;
			}
			ids.push(0);
		});

		key1 = 0;
		key2 = 1;

		var years = data.keys[key2];

		// loop over countries
		$.each(data.keys[key1], function(k,v) {
			ids[key1] = k;
			var countryName=v.toString();
			var isoCode=countryName;
			if (isoCode!=undefined) isoCode=isoCode.toLowerCase();
			if (isoCode!=undefined&&lang_countries[isoCode]!=undefined) {
				if ($(window).width() < 450){
				  if (ISOcodes=='CRScodes') {countryName=CRStoISO3[isoCode];}
				  else {countryName=isoCode.toUpperCase();}
				} else {
                  countryName=lang_countries[isoCode];
                }
			}

			var items = [];
			// loop over years
			$.each(data.keys[key2], function(k,v) {
				ids[key2] = k;
				var d = data.data;
				$.each(ids, function(k,v) { d=d[v]; });
				items.push(d);
			});

			var lastValue, lastYear;
			// find last year with value
			for (var k=years.length-1; k>=0; k--) {
				var v=years[k];
				if (selectedYear != -1 && selectedYear < v) continue;
				if (lastYear == null) lastYear="";
                                else lastYear=" ("+v+")";
				if (!isNaN(parseFloat(items[k]))) {
					lastValue=k;
					break;
				}
			}

			if (lastValue == null) return;

			var countryobj = {
			    name: countryName+lastYear,
			    iso: isoCode.toUpperCase(),

			    y: parseFloat(items[lastValue]),

			    tooltip: commaSeparateNumber(parseFloat(items[lastValue])),

			};

            if (isoCode=='oecd') countryobj.color=oecdColor;
			if (isoCode=='OECD') countryobj.color=oecdColor;
			if (isoCode=='20001') countryobj.color=oecdColor;
			if (isoCode=='average') countryobj.color=oecdColor;

			self.goaldata.push(countryobj);

		});

		self.goaldata.sort(compareBy('y'));
		self.drawChart(self.goaldata,self.options);

	}

	this.drawChart=function(data,options) {
		self.curData=data;
		defaultOptions={
			chart: {
				renderTo: self.container,
				type: 'column',
				margin: marginsBar,
                backgroundColor:'rgba(255, 255, 255, 0.1)'
			},
			xAxis: {
				categories: (function(data){ var x=[]; $(data).each(function(i,e){ x.push(e.name) }); return x; }(data)),
				labels: {enabled: false},
				tickWidth: 0
			},
			yAxis: {
				title: {text: null},
				labels: labelsYaxis,
                gridLineWidth: gridLinesYaxis
			},
			series: [{data: data,}],
			tooltip: {enabled: tooltips},

			plotOptions: {
				series: {
					borderWidth: '0',
					shadow: false,
					animation: true,
					pointPadding: 0.15,
					groupPadding: 0,
                    dataLabels: {
                        enabled: false,
                        overflow: 'none',
                        crop: false,
                        allowOverlap: true,
                    }
				},
			column: {stacking: 'normal'}

			},
			colors: defaultColor,
			legend: {enabled: false},
			credits: {enabled: false},
			exporting: {enabled: false},
			title : {
				text: self.title,
				align: 'left',
				x: 0,
				style: { fontSize: '14px' }
				},
		}

		if (options) defaultOptions=objectUnion(defaultOptions,options);

		self.chart = new Highcharts.Chart(defaultOptions);
	}

	this.tooltipChartUrl=function(data) {return '';}

	this.sortChart=function(comparator) {
		self.goaldata.sort(compareBy(comparator));
		self.drawChart(self.goaldata,self.options);
	}

	this.matchSort=function(otherChart) {
		var newGoalData=[]
		var goalDataDict={};
		$.each(self.goaldata,function(ckey,cval) {
			goalDataDict[cval.name]=cval;
		})
		$.each(otherChart.goaldata,function(ckey,cval) {
			var dataItem=goalDataDict[cval.name];
			if (dataItem!=undefined) newGoalData.push(dataItem);
		});
		self.drawChart(newGoalData,self.options);
	}

	this.highlightCountry=function(iso,color) {
		var datapoint;
		var i;
		isoArr=[iso.toUpperCase()];
		for (i=0;i<self.curData.length;i++) {
			datapoint=self.curData[i];
			if (isoArr.indexOf(datapoint.iso.toUpperCase())>=0) {
				datapoint.oldColor=datapoint.color;
				datapoint.color=color;
			}
		}
		self.chart.series[0].setData(self.curData,true);
	}

	this.highlightSecondaryCountries=function(isos,color) {
		var datapoint;
		var i;
		isoArr=[];
                $.each(isos,function(k,v) { isoArr.push(v.toUpperCase()); });
		for (i=0;i<self.curData.length;i++) {
			datapoint=self.curData[i];
			if (isoArr.indexOf(datapoint.iso.toUpperCase())>=0) {
				datapoint.color=color;
			}
		}
		self.chart.series[0].setData(self.curData,true);
	}

	this.removeHighlights=function(color) {
		var datapoint;
		var i;
		for (i=0;i<self.curData.length;i++) {
			datapoint=self.curData[i];
			if (datapoint.color==color) datapoint.color = datapoint.oldColor;

		}
		self.chart.series[0].setData(self.curData,true);
	}

	this.removeHighlightCountry=function(iso) {
		var datapoint;
		var i;
		isoArr=[iso.toUpperCase()];
		for (i=0;i<self.curData.length;i++) {
			datapoint=self.curData[i];
			if (isoArr.indexOf(datapoint.iso.toUpperCase())>=0) {
				datapoint.color=datapoint.oldColor;
			}
		}
		self.chart.series[0].setData(self.curData,true);
	}


}

