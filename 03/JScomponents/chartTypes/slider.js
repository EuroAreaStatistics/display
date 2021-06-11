

var height = "innerHeight" in window
               ? window.innerHeight
               : document.documentElement.offsetHeight;

var width = "innerWidth" in window
               ? window.innerWidth
               : document.documentElement.offsetWidth;

var lableSize = ((width*height)/100000)+9;


$(document).ready(function() {


    var background = ['#04629a','#4b7520'/*,'#8e4402'*/,'#804080'];

    var colors = [
        ['#0bb89c','#ffc20e','#d70b8c','#f47920','#00937d','#da2128'],
    ];

    
    var labels = {
        title : {
                "color":"#ffffff",
                "font-size":lableSize*1.1+"px"
                },
        subtitle : {
                "color":"#ffffff",
                "font-size":lableSize*0.9+"px"
                },
        legendStyle : {
                "color":"#ffffff",
                "fontWeight" : "normal",
                "fontSize": lableSize*0.8+"px"
                },
        dataLabelsStyle : {
                "color":"#ffffff",
                "fontSize": lableSize*0.8+"px"
                },
        axisLabelsStyle : {
                "color":"#ffffff",
                "fontSize": lableSize+"px"
                },
    };


    $('body').append('<div id="fullpage">')

	if(window!=top && iOS()==true) {
		var i=0;
		var tab = config.project.tabs[0];
		var index = 1;

		if (config.tabs[tab].charts.length == 1) {
			if (config.tabs[tab].teaser[lang] == '') {
				addChartSection (config,lang,background,tab,i,'oneChartSolo');
			} else {
				addChartSection (config,lang,background,tab,i,'oneChart');
			}
		} else if (config.tabs[tab].charts.length == 2) {
			addChartSection (config,lang,background,tab,i,'twoCharts');
		}
		if (i<background.length-1) {
			i++;
		} else {
			i=0;
		}

		if(config.tabs[config.project.tabs[index-1]].charts.length == 1){
			addOneChart (config,index,colors,lang,labels);
		} else if (config.tabs[config.project.tabs[index-1]].charts.length == 2) {
			addTwoCharts (config,index,colors,lang,labels);
		}

	} else {

		var i=0;
		$.each(config.project.tabs, function (k,tab) {
			if (config.tabs[tab].charts.length == 1) {
				if (config.tabs[tab].teaser[lang] == '') {
					addChartSection (config,lang,background,tab,i,'oneChartSolo');
				} else {
					addChartSection (config,lang,background,tab,i,'oneChart');
				}
			} else if (config.tabs[tab].charts.length == 2) {
				addChartSection (config,lang,background,tab,i,'twoCharts');
			}
			if (i<background.length-1) {
				i++;
			} else {
				i=0;
			}
		});

		$('#fullpage').fullpage({
			navigation: true,
			navigationPosition: 'right',
			continuousVertical: true,
	//		autoScrolling: false,
			afterLoad: function(anchorLink, index){
				if(config.tabs[config.project.tabs[index-1]].charts.length == 1){
					addOneChart (config,index,colors,lang,labels);
				} else if (config.tabs[config.project.tabs[index-1]].charts.length == 2) {
					addTwoCharts (config,index,colors,lang,labels);
				}
			},
			onLeave: function(index, nextIndex, direction){
				if(config.tabs[config.project.tabs[index-1]].charts.length == 1){
					removeOneChart(index);
				} else if (config.tabs[config.project.tabs[index-1]].charts.length == 2) {
					removeTwoCharts (index);
				}
			}
		});


	}






//    setInterval(moveSlider,10000);
    var j=1;
    function moveSlider () {

        if (j<config.project.tabs.length) {
            j++;
            $.fn.fullpage.moveSectionDown();
        } else {
            j=1;
            $.fn.fullpage.silentMoveTo(1, 1)
        }

    }

    $(".detailsSection").click(function(){
        $('.showDetails').toggle();
        $('.hideDetails').toggle();
        $(".splitTextSection").toggle();
    });

    $(".nextSection").click(function(){
        moveSlider () ;
    });


});

function addChartSection (config,lang,background,tab,tabIndex,mode) {

    $('#fullpage').append('<div class="section section'+tab+'">');
    $('.section'+tab).css({'background-color':background[tabIndex]});
    $('.section'+tab).append('<div class="headerWrapper">');
    $('.section'+tab).append('<div class="mainWrapper">');

    if (mode == 'twoCharts') {
        $('.section'+tab+' .mainWrapper').append('<div id="containerA'+tab+'" class="splitChartSection">');
        $('.section'+tab+' .mainWrapper').append('<div id="containerB'+tab+'" class="splitChartSection">');
    } else if (mode == 'oneChartSolo') {
        $('.section'+tab+' .mainWrapper').append('<div id="container'+tab+'" class="centerChartSection">');
    } else {
        $('.section'+tab+' .mainWrapper').append('<div id="container'+tab+'" class="splitOneChartSection">');
        $('.section'+tab+' .mainWrapper').append('<div id="text'+tab+'" class="splitTextSection">');
    }


    if (width >= height) {

        $('.section'+tab).append('<div class="footerWrapper">');
        $('.section'+tab+' .footerWrapper').html('<span>Find more data stories at <a href="http://www.compareyourcountry.org">www.compareyourcountry.org</a></span>');


        $('.headerWrapper').css({
            'height':(height*0.125)-1,
            'padding-top':height*0.0125,
            'padding-bottom':height*0.0125,
            'padding-right':width*0.05,
            'padding-left':width*0.05,
            'width':width*0.90,
        });
        $('.centerChartSection').css({
            'height':height*0.70,
            'width':(width*0.65)-1,
            'padding-top':height*0.025,
            'padding-bottom':height*0.025,
            'padding-right':width*0.175,
            'padding-left':width*0.175,
        });
        $('.splitChartSection, .splitOneChartSection').css({
            'height':height*0.70,
            'float':'left',
            'width':(width*0.45)-1,
            'padding-top':height*0.025,
            'padding-bottom':height*0.025,
            'padding-right':width*0.025,
            'padding-left':width*0.025,
        });
        $('.splitTextSection').css({
            'height':height*0.60,
            'float':'left',
            'width':(width*0.40)-1,
            'padding-top':height*0.125,
            'padding-bottom':height*0.025,
            'padding-right':width*0.05,
            'padding-left':width*0.05,
        });
        $('.footerWrapper')
            .css({
                'height':(height*0.10)-1,
                'width':width,
            })
            .textfill({
                maxFontPixels : 18,
            });


    } else {

        if (mode == 'oneChart') {

            $('.section'+tab).append('<div class="footerWrapper">'+
                                     '<div class="nextSection nextSectionSmall">Next slide</div>'+
                                     '<div class="detailsSection">'+
                                     '<span class="showDetails">Show details</span><span class="hideDetails">Hide details</span>'+
                                     '</div>'
                        );

        } else {

            $('.section'+tab).append('<div class="footerWrapper">'+
                                     '<div class="nextSection nextSectionLarge">Next slide</div>'
                        );

        }
        $('.headerWrapper').css({
            'height':(height*0.09)-1,
            'padding-top':height*0.01,
            'padding-bottom':height*0.0,                 //10
            'padding-right':width*0.05,
            'padding-left':width*0.05,
            'width':width*0.90,
        });
        $('.centerChartSection').css({
            'height':height*0.78,
            'width':(width*0.95)-1,
            'padding-top':height*0.01,
            'padding-bottom':height*0.01,                  //75
            'padding-right':width*0.025,
            'padding-left':width*0.025,
        });
        $('.splitChartSection').css({
            'height':height*0.38,
            'float':'left',
            'width':(width*0.95)-1,
            'padding-top':height*0.01,                     //37.5
            'padding-bottom':height*0.01,
            'padding-right':width*0.025,
            'padding-left':width*0.025,
        });
        $('.splitOneChartSection').css({
            'height':height*0.78,
            'float':'left',
            'width':(width*0.95)-1,
            'padding-top':height*0.01,
            'padding-bottom':height*0.01,                  //75
            'padding-right':width*0.025,
            'padding-left':width*0.025,
        });
        $('.splitTextSection').css({
            'position': 'absolute',
            'background-color' : 'black',
            'opacity' : '0.8',
            'float':'left',
            'height':height*0.4,
            'bottom' : (height*0.1)-1,
            'width':(width*0.90)-1,
            'padding-top':height*0.025,
            'padding-bottom':height*0.025,                  //20
            'padding-right':width*0.05,
            'padding-left':width*0.05,
        }).hide();
        $('.footerWrapper').css({
            'height':(height*0.1)-1,                       //10
            'width':width,
        });
        $('.nextSectionSmall, .detailsSection').css({
            'height':(height*0.1)-1,                       //0
            'width': width*0.5,
        });
        $('.nextSectionLarge').css({
            'height':(height*0.1)-1,                       //0
            'width': width,
        });

    }

    if (mode != 'twoCharts') {
        $('.section'+tab+' .splitTextSection').html('<span>'+config.tabs[tab].teaser[lang]+'</span>');
        $('.section'+tab+' .splitTextSection').textfill({
            maxFontPixels : 25,
        });
    }

    $('.section'+tab+' .headerWrapper').html('<span>'+config.tabs[tab].title[lang]+'</span>');

    $('.headerWrapper').textfill({});


};

function addOneChart (config,tab,colors,lang,labels) {

  tab = config.project.tabs[tab-1];
	var chart = config.charts[config.tabs[tab].charts[0]];

//check for country lables before setting maximum number of countries
  chart = getMaxCountries (height/3,chart,'undefined',lang);

	var tabLabels = config.tabs[tab].labels;
	var translatedLabels = window.lang_countries;

  if (tabLabels) {
		$.each(tabLabels, function(key,value){
			translatedLabels[key.toLowerCase()] = value[lang];
		})
	}


    if (config.tabs[tab].template == 'sbar') {
        addBarChartSlider(chart,'container'+tab,colors[0],lang,labels,translatedLabels);
    } else if (config.tabs[tab].template == 'scolumn') {
        addColumnChartSlider(chart,'container'+tab,colors[0],lang,labels,translatedLabels);
    } else if (config.tabs[tab].template == 'slines') {
        addLineChartSlider(chart,'container'+tab,colors[0],lang,labels,translatedLabels);
    } else if (config.tabs[tab].template == 'spie') {
        addPieChartSlider(chart,'container'+tab,colors[0],lang,labels,translatedLabels);
    }

};


function addTwoCharts (config,tab,colors,lang,labels) {

    var tabIndex = tab-1;
    tab = config.project.tabs[tab-1];
	var chart0 = config.charts[config.tabs[tab].charts[0]];
	var chart1 = config.charts[config.tabs[tab].charts[1]];

	var tabLabels = config.tabs[tab].labels;
	var translatedLabels = window.lang_countries;

    if (tabLabels) {
		$.each(tabLabels, function(key,value){
			translatedLabels[key.toLowerCase()] = value[lang];
		})
	}

    if (config.tabs[tab].template == 'sbar') {
        addBarChartSlider(chart0,'containerA'+tab,colors[0],lang,labels,translatedLabels);
        addBarChartSlider(chart1,'containerB'+tab,colors[0],lang,labels,translatedLabels);
    } else if (config.tabs[tab].template == 'scolumn') {
        addColumnChartSlider(chart0,'containerA'+tab,colors[0],lang,labels,translatedLabels);
        addColumnChartSlider(chart1,'containerB'+tab,colors[0],lang,labels,translatedLabels);
    } else if (config.tabs[tab].template == 'slines') {
        addLineChartSlider(chart0,'containerA'+tab,colors[0],lang,labels,translatedLabels);
        addLineChartSlider(chart1,'containerB'+tab,colors[0],lang,labels,translatedLabels);
    } else if (config.tabs[tab].template == 'spie') {
        addPieChartSlider(chart0,'containerA'+tab,colors[0],lang,labels,translatedLabels);
        addPieChartSlider(chart1,'containerB'+tab,colors[0],lang,labels,translatedLabels);
    }

};


function removeOneChart (tab) {
    tab = config.project.tabs[tab-1];
    if ($('#container'+tab).highcharts) {
        $('#container'+tab).highcharts().destroy();
    }
};


function removeTwoCharts (tab) {
    tab = config.project.tabs[tab-1];
    if ($('#containerA'+tab).highcharts) {
        $('#containerA'+tab).highcharts().destroy();
    }
    if ($('#containerB'+tab).highcharts) {
        $('#containerB'+tab).highcharts().destroy();
    }
};


function iOS() {
	var iDevices = [
	  'iPad Simulator',
	  'iPhone Simulator',
	  'iPod Simulator',
	  'iPad',
	  'iPhone',
	  'iPod'
	];
	while (iDevices.length) {
		if (navigator.platform === iDevices.pop()){ return true; }
	}
	return false;
}


