
function updateCountryProfileSimple(profileCountry) {
// update country profile with tabs
// tabs include charts or tables only

  $(".noteCountryName").text(lang_countries_titles[profileCountry.toLowerCase()]|| '');
  $.each( window.subTabs, function(key,value) {
    $("#subTab"+key).html('');
  });

  addCharts();

  function addCharts() {
    $.each( window.subTabs, function(subTab,subTabVal) {
      $.each(subTabVal.content, function(section,val) {
        $("#subTab"+subTab).append('<div id="sec'+subTab+'-'+section+'" class="section"><div class="headingElement"><h4><span class="subHeadingText">');
//        $('#sec'+subTab+'-'+section+' .headingElement h4 .subHeadingText').html(val);
        if (subTabVal.charts != undefined){
          addChartSections (subTabVal,subTab,section,profileCountry,val,'chartSectionMain');
        }
      });
    });
    largeContainer();
  };

  function addChartSections (subTabVal,subTab,section,profileCountry,sectionName,sectionLevel) {

    var complexCharts=[];
    var complexTable;

    $.each(subTabVal.charts, function(chart,config) {
      var chartHeader = '<div class="'+sectionLevel+'"><div class="chartTitle">'+subTabVal.ConfigSubCharts[chart].title+'</div>'+
                        '<div class="chartSubTitle">'+subTabVal.ConfigSubCharts[chart].definition+
                        ', '+subTabVal.ConfigSubCharts[chart].options.tooltipUnit+'</div>'+
                        '<div style="height:1000px" class="chartContainer" id="container'+chart+'-'+subTab+'-'+section+'">';
      if (config.chartType == 'simpleTable') {
        $('#sec'+subTab+'-'+section).append(addSimpleTable(subTabVal.ConfigSubCharts[chart],profileCountry,config.legendType));
      }
      if (config.chartType == 'complexTable') {
        complexCharts.push(subTabVal.ConfigSubCharts[chart]);
        complexTable=true;
      }
      if (config.chartType == 'sortedBarChart') {
        values = filterLocation(subTabVal.ConfigSubCharts[chart],[profileCountry.toLowerCase()]);
        values.data = flipData(values.data);
        if (values.data.data[0] != undefined) {
          $('#sec'+subTab+'-'+section).append(chartHeader);
          addBarChart(values,'container'+chart+'-'+subTab+'-'+section);
        }
      }
    });
    if (complexTable == true) {

      var complexTableLabel = {
        'en' : 'Click on column headers to change table sorting. Pass cursor over commodity names and export restriction symbols for detailed definitions and explanations.',
        'cn' : '点击列标题改变表格排序。在商品名称和出口限制的符号上点击光标查询详细定义和解释。',
      }

      $('#sec'+subTab+'-'+section).append('<div class="helpTextComplexTable">'+complexTableLabel[lang]+'</div>');
      $('#sec'+subTab+'-'+section).append(addComplexTable(complexCharts,profileCountry));

      $.tablesorter.formatInt = function(s) {
        var i = parseInt(s);
        return (isNaN(i)) ? null : i;
      };
      $.tablesorter.formatFloat = function(s) {
        var i = parseFloat(s);
        return (isNaN(i)) ? null : i;
      };

      $(".complexTable").tablesorter({
        sortList: [[0,0]],
        headers:{
          1:{sorter:false}
        },
        textExtraction: function (node) {
          var txt = $(node).text();
          txt = txt.replace('--', ''); // EM DASH
          txt = txt.replace('*', '');
          return txt;
        }
      });

    }
  }

}


