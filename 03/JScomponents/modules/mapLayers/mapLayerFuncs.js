
var topLarge = 80;
var heightSmall = 85

if ($(window).width() > 475){
  topLarge = 92;
  heightSmall = 85
} if ($(window).width() > 650){
  topLarge = 100;
  heightSmall = 70
}

function largeContainer() {
  $("#topContainer").css({"height": "auto"});
  $("#topContainer").css({'top': topLarge+"px"});
  $("#itemContainer").fadeIn();
  $("#item1Container").fadeOut();
  $("#item2Container").fadeOut();
  $("#mapLegendContainer").fadeOut();
  $(".map-legend").css({"padding-top": "30px"});
  mapdataviz.closePopup();
}

function largeContainerPDF() {
  $("#itemContainer").fadeIn();
}


function smallContainer() {
  $("#topContainer").animate({height: heightSmall+"px"});
  $("#topContainer").css({"top": "auto"});
  $("#itemContainer").fadeOut();
  $("#item1Container").fadeIn();
  $("#item2Container").fadeOut();
  $("#mapLegendContainer").fadeIn();
  $(".map-legend").css({"padding-top": "0px"});
}

function startContainer() {
  $("#topContainer").css({"height": "auto"});
  $("#topContainer").css({'top': topLarge+"px"});
  $("#itemContainer").fadeOut();
  $("#item1Container").fadeOut();
  $("#item2Container").fadeIn();
  $("#mapLegendContainer").fadeOut();
  $(".map-legend").css({"padding-top": "30px"});
  mapdataviz.closePopup();
}


