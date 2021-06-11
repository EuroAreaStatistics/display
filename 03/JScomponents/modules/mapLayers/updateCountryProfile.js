
function updateCountryProfile(profileCountry,mode) {
// update country profile with tabs
// tabs include different text sections
// charts can be integrated as additional text sections

  $(".noteCountryName").text(lang_countries_titles[profileCountry.toLowerCase()]|| '');
  $.each( window.subTabs, function(key,value) {
    $("#subTab"+key).html('');
  });

  $.ajax({
    dataType: "json",
    url: baseURL+"/api-notes?project="+project+"&lg="+lang+"&cr="+profileCountry.toLowerCase(),
    success: ajaxSuccess,
    error: function (xhr, ajaxOptions, thrownError) {
      if (xhr.status == '404') {
        alert ('Country profile not available.')};
    }
  });

  function ajaxSuccess(data) {
    if (data != null) {
      $.each( window.subTabs, function(subTab,subTabVal) {
        $.each(subTabVal.content[window.lang], function(section,val) {
          $("#subTab"+subTab).append('<div id="sec'+subTab+'-'+section+'" class="section"><div class="headingElement"><h4><span class="subHeadingText">');
          $('#sec'+subTab+'-'+section+' .headingElement h4 .subHeadingText').html(val);
          var subSection = 0;
          if (data[val] != undefined) {
            $.each( data[val], function(key,content) {
              if (key == 'h1 text') {
                $.each(content,function(element,parts){
                  $('#sec'+subTab+'-'+section).append('<p id="textSec'+subTab+'-'+section+'-'+element+'" class="textElement">');
                  $('#textSec'+subTab+'-'+section+'-'+element).html(parts);
                });
              }
              if (subTabVal.charts != undefined){
                addChartSections (subTabVal,subTab,section,profileCountry,val,'chartSectionMain');
              }

              if (key != 'h1 text') {
                $('#sec'+subTab+'-'+section).append('<h5 id="headerSec'+subTab+'-'+section+'-'+subSection+'" class="subSection"><table><tr><td>');
                $('#headerSec'+subTab+'-'+section+'-'+subSection+' table tr td').html(key);
                var subSubSection = 0;
                $.each( content, function(subkey,subcontent) {
                  if (subkey == 'h2 text') {
                    $.each(subcontent, function(key,content) {
                      $('#sec'+subTab+'-'+section).append('<p id="textSec'+subTab+'-'+section+'-'+subSection+'-'+key+'" class="textElement">');
                      if (subTabVal.display != null && subTabVal.display == 'expanded') {
                        $('#textSec'+subTab+'-'+section+'-'+subSection+'-'+key).html(content);
                      } else {
                        $('#textSec'+subTab+'-'+section+'-'+subSection+'-'+key).html(content).hide();
                      }
                    });
                    if (subTabVal.charts != undefined) {
                      if (subTabVal.display != null && subTabVal.display == 'expanded') {
                        addChartSections (subTabVal,subTab,section,profileCountry,key,'chartSectionMain');
                      } else {
                        addChartSections (subTabVal,subTab,section,profileCountry,key,'chartSection');
                      }
                    }
                  } else {
                    $.each(subcontent, function(key,content) {
                      $('#sec'+subTab+'-'+section).append('<h6 id="headerSec'+subTab+'-'+section+'-'+subSection+'-'+subSubSection+'" class="subSection">');
                      $('#headerSec'+subTab+'-'+section+'-'+subSection+'-'+subSubSection).text(subkey).hide();
                      $('#sec'+subTab+'-'+section).append('<p id="textSec'+subTab+'-'+section+'-'+subSection+'-'+subSubSection+'-'+key+'" class="textElement">');
                      $('#textSec'+subTab+'-'+section+'-'+subSection+'-'+subSubSection+'-'+key).html(content).hide();
                    });
                  }
                  subSubSection++;
                });
              }
              subSection++;
            });
        }
        });
        if (subTabVal.display == null) {
          $("#subTab"+subTab+" .section h5 table tr").append(expand);
        }
      });
    } else {
      alert('Country profile is not available.');
    }
    $(".tab a").attr('target','_blank');
    $(".expandCollaps").append(expandElements);
    $("h5").click(function() {
        var parent = this;
        $(parent).nextUntil("h5","p").slideToggle(500, function(){} );
        $(parent).nextUntil("h5","h6").slideToggle(500, function(){} );
        $(parent).nextUntil("h5",".simpleTable").toggle();
        $(parent).nextUntil("h5",".chartSection").toggle();
        $(parent).find(".expand").toggle();
        $(parent).find(".collaps").toggle();
    });
    if (mode == 'pdf') {
      largeContainerPDF();
    } else {
      largeContainer();
    }
  };

  var expand =  '<td class="expandCollaps">'
  var expandElements = '<span class="controls expand expandText">[expand&nbsp;section]</span><span class="controls collaps expandText">[collapse&nbsp;section]</span>'

  function addChartSections (subTabVal,subTab,section,profileCountry,sectionName,sectionLevel) {
    $.each(subTabVal.charts, function(chart,config) {
      var chartHeader = '<div class="'+sectionLevel+'"><div class="chartTitle">'+subTabVal.ConfigSubCharts[chart].title+'</div>'+
                        '<div class="chartSubTitle">'+subTabVal.ConfigSubCharts[chart].definition+
                        ', '+subTabVal.ConfigSubCharts[chart].options.tooltipUnit+'</div>'+
                        '<div style="height:200px" class="chartContainer" id="container'+chart+'-'+subTab+'-'+section+'">';
      if (config.section[window.lang] == sectionName) {
        if (config.chartType == 'simpleTable') {
          $('#sec'+subTab+'-'+section).append(addSimpleTable(subTabVal.ConfigSubCharts[chart],profileCountry,config.legendType));
        }
        if (config.chartType == 'lineChart') {
          values = filterLocation(subTabVal.ConfigSubCharts[chart],[profileCountry.toLowerCase()])
          if (values.data.data[0] != undefined) {
            $('#sec'+subTab+'-'+section).append(chartHeader);
            addLineChart(values,'container'+chart+'-'+subTab+'-'+section);
          }
        }
        if (config.chartType == 'columnChart') {
          $('#sec'+subTab+'-'+section).append(chartHeader);
          addColumnChart(filterYear(subTabVal.ConfigSubCharts[chart],["2012"]),'container'+chart+'-'+subTab+'-'+section);
          highlightDataPoint(profileCountry.toLowerCase(),'blue',0,'#container'+chart+'-'+subTab+'-'+section);
        }
      }
    });
  }

}


