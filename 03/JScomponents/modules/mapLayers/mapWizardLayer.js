
$(document).ready(function() {

  var tab = wizardConfig.project.tabs[page];
  var subTabsType = wizardConfig.tabs[tab].subTabsType;
  if (wizardConfig.tabs[tab].teaserLayer && window.secondVisit == false) {
    startContainer();
  } else {
    smallContainer();
  }
  $(".countryProfilLink").show();
  $(".teaserTitle2").text(' - '+lang_labels.TabCountryNote);

  $.each( subTabs, function(key,value) {
    $('#panelNav').append('<a id="subTabButton'+key+'" href="#subTab'+key+'">')
    $('#itemContainer').append('<div id="subTab'+key+'" class="tab">')
    $('#subTabButton'+key+'').text(value.title[lang])
  });

  $("#subTabButton0").addClass('active');
  $("#subTab0").addClass('active');

//on select from map tooltip in map mode
  $(document).on('click', '#countryProfileMap', function(){
    if (subTabsType == 'simple') {
      updateCountryProfileSimple($(this).attr('value').toLowerCase());
    } else {
      updateCountryProfile($(this).attr('value').toLowerCase());
    }
    mapdataviz.closePopup();
  });

//on select from map tooltip in map mode
  $(document).on('click', '#euProfileMap', function(){
    if (subTabsType == 'simple') {
      updateCountryProfileSimple($(this).attr('value').toLowerCase());
    } else {
      updateCountryProfile($(this).attr('value').toLowerCase());
    }
    mapdataviz.closePopup();
  });

//close large layer and show small layer
  $('.closeLayer').click(smallContainer);

// switch between subnav tabs
  $('#panelNav').on('click', 'a', function(){
    $(this).addClass('active').siblings('a.active').removeClass('active');
    $($(this).attr('href')).addClass('active').siblings('.tab.active').removeClass('active');
    return false;
  });

// add PDF option (to be developed)
//  if (pdfWizard != null) {
//    $('#panelNav').append('<a id="subTabButtonPDF" href="#subTabPDF">')
//    $('#itemContainer').append('<div id="subTabPDF" class="tab">')
//    $('#subTabPDF').append(
//        '<h4>Generate a PDF with your selection</h4>'+
//		'<p class="buttons"><button id="pdfButton">generate PDF</button>'
//      )
//    $('#subTabButtonPDF').text('Create PDF')
//
//    $('#pdfButton').click(function(){
//    generatePDF(pdfWizard);
//    });
//  }

});

