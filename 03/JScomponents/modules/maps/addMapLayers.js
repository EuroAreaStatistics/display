
//add mapLayers for the different display types (bubbles, colored bublbes (simple and complex), colored shapes)

function addMapLayers (bubbleMap,mapdataviz,flowStatus) {
  var bubbles=[];
  var layers={};
  var layerName=[];
  
  if (bubbleMap == 'multiColored') {
// used on template 'multipleColoredBubbles'
// A colored bubble map. The first chart sets the bubble size, the second the bubble color. The second dimension (YEARS) is represented as a separate indicator

    L.geoJson(
      mapShapes, {
        style: getBasicCountryShapesStyle,
        clickable: false
      }).addTo(mapdataviz);
    var i=0;
    var matchIndex = [];
    $.each(DataforMap, function (key, value) {
      $.each(DataforMap1, function (key1, value1) {
        if (value1.title == value.title) {
          matchIndex[key] = key1+1;
        }
      });

      if (matchIndex[key]) {
        var colorIndicator = DataforMap1[matchIndex[key]-1]
      } else {
        var colorIndicator = null;
      }

      bubbles[i] = L.geoJson(
        mapCentroides, {
          pointToLayer: bubblesSizeColorGeneric(value,colorIndicator),
          onEachFeature: highlightAndPopup
        });
      layerName[$('<div>').html(DataforMap[i]['title']).text()] = bubbles[i];
      i++;
    });

    bubbles[0].addTo(mapdataviz);
    updateMultiLegend(DataforMap[0],'size');
    updateMultiLegend(DataforMap1[matchIndex[0]-1],'color');
//global variables to define map layer (used for popup window)
    window.mapLayerIndex = 0;
    window.mapLayerMatchIndex = matchIndex;
    if (DataforMap.length > 1) {
      if ($(window).width() < 750) {
        L.control.layers(layerName,null,{collapsed: true}).addTo(mapdataviz);
      } else {
        L.control.layers(layerName,null,{collapsed: false}).addTo(mapdataviz);
      }
    }
    $(".leaflet-control-layers-selector").click(function(){
      var index = $(this).parent().index();
      updateMultiLegend(DataforMap[index],'size');
      updateMultiLegend(DataforMap1[matchIndex[index]-1],'color');
      mapdataviz.closePopup();
      window.mapLayerIndex = index;
    });

  } else if (bubbleMap == 'colored') {
// first indicator bubble size, all other indicators bubble color
    L.geoJson(
      mapShapes, {
        style: getBasicCountryShapesStyle,
        clickable: false
      }).addTo(mapdataviz);
    for (i=0;i<DataforMap.length-1;i++) {
      bubbles[i] = L.geoJson(
        mapCentroides, {
          pointToLayer: bubblesSizeColorGeneric(DataforMap[0],DataforMap[i+1]),
          onEachFeature: highlightAndPopup
        });
      layerName[$('<div>').html(DataforMap[i+1]['title']).text()] = bubbles[i]
    }
    bubbles[0].addTo(mapdataviz);
    updateLegend(DataforMap[0],'size');
    updateLegend(DataforMap[1],'color');
    if (DataforMap.length-1 > 1) {
      if ($(window).width() < 750) {
        L.control.layers(layerName,null,{collapsed: true}).addTo(mapdataviz);
      } else {
        L.control.layers(layerName,null,{collapsed: false}).addTo(mapdataviz);
      }
    }
    $(".leaflet-control-layers-selector").click(function(){
      var index = $(this).parent().index();
      updateLegend(DataforMap[index+1],'color');
    });

  } else if (bubbleMap == 'coloredPairUnpair') {
// pair indicators bubble size, unpair indicators bubble color
    L.geoJson(
      mapShapes, {
        style: getBasicCountryShapesStyle,
        clickable: false
      }).addTo(mapdataviz);
    for (i=0;i<DataforMap.length/2;i++) {
      bubbles[i] = L.geoJson(
        mapCentroides, {
          pointToLayer: bubblesSizeColorGeneric(DataforMap[i*2],DataforMap[(i*2)+1]),
          onEachFeature: highlightAndPopup
        });
      layerName[$('<div>').html(DataforMap[i*2]['title']).text()] = bubbles[i]
    }
    bubbles[0].addTo(mapdataviz);
    updateLegend(DataforMap[0],'size');
    updateLegend(DataforMap[1],'color');
    if (DataforMap.length-1 > 1) {
      if ($(window).width() < 750) {
        L.control.layers(layerName,null,{collapsed: true}).addTo(mapdataviz);
      } else {
        L.control.layers(layerName,null,{collapsed: false}).addTo(mapdataviz);
      }
    }
    $(".leaflet-control-layers-selector").click(function(){
      var index = $(this).parent().index();
      updateLegend(DataforMap[index*2],'size');
      updateLegend(DataforMap[(index*2)+1],'color');
    });


  } else if (bubbleMap == 'simple') {

    L.geoJson(
      mapShapes, {
        style: getBasicCountryShapesStyle,
        clickable: false
      }).addTo(mapdataviz);
    for (i=0;i<DataforMap.length;i++) {
      bubbles[i] = L.geoJson(
        mapCentroides, {
          pointToLayer: bubblesSimpleGeneric(DataforMap[i]),
          onEachFeature: highlightAndPopup
        });
      layerName[$('<div>').html(DataforMap[i]['title']).text()] = bubbles[i]
    }
    bubbles[0].addTo(mapdataviz);
//add flows
    if (flowStatus=='flow') {
      updateLegend(DataforMap[0],'flows');
      updateFlowData(DataforMap[0]['code']);
//add global variable for value behind bubble size as a scaling reference for flow lines
      window.maxBubble = DataforMap[0]['max'];
    } else {
      updateLegend(DataforMap[0],'size');
    }
    
    if (DataforMap.length > 1) {
      if ($(window).width() < 750) {
        L.control.layers(layerName,null,{collapsed: true}).addTo(mapdataviz);
      } else {
        L.control.layers(layerName,null,{collapsed: false}).addTo(mapdataviz);
      }
    }
    $(".leaflet-control-layers-selector").click(function(){
      var index = $(this).parent().index();
//add flows
      if (flowStatus=='flow') {
        updateFlowData(DataforMap[index]['code']);
        clearFlows();
        clearFlowLegend();
        updateLegend(DataforMap[index],'flows');
//add global variable for value behind bubble size as a scaling reference for flow lines
        window.maxBubble = DataforMap[index]['max'];
      } else {
        updateLegend(DataforMap[index],'size');
      }
    });

  } else {

    var shapes=[];
    var layerGroup=[];
    var mapSubPoints = getMapSubPoints(mapCentroides);

    for (i=0;i<DataforMap.length;i++) {
      shapes[i] = L.geoJson(
        mapShapes, {
          style: getColorCountryShapesStyleGeneric(DataforMap[i]),
          onEachFeature: highlightAndPopup,
          clickable: false
        });
      if (mapSubPoints != undefined) {
        bubbles[i] = L.geoJson(
          mapSubPoints, {
            pointToLayer: bubblesSimpleColorGeneric(DataforMap[i]),
            onEachFeature: highlightAndPopup
          });
        layerGroup[i] = L.layerGroup([shapes[i],bubbles[i]])
      } else {
        layerGroup[i] = L.layerGroup([shapes[i]])
      }
      // force the layer control to display the indicators in the correct order
      L.stamp(layerGroup[i]);
      layerName[$('<div>').html(DataforMap[i]['title']).text()] = layerGroup[i];
    }
    layerGroup[DataforMapAll.defaultLayer].addTo(mapdataviz);
    updateLegend(DataforMap[DataforMapAll.defaultLayer],'color');
    if (DataforMap.length > 1) {
      if ($(window).width() < 750 || embed == 2) {
        L.control.layers(layerName,null,{collapsed: true}).addTo(mapdataviz);
      } else {
        L.control.layers(layerName,null,{collapsed: false}).addTo(mapdataviz);
      }
    }
    $(".leaflet-control-layers-selector").click(function(){
      var index = $(this).parent().index();
      updateLegend(DataforMap[index],'color');
    });

  }

}
