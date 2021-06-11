var colorIdx = 0,
    flowGroup = L.layerGroup(),
    activeLayer = "";
function clickFlow(e) {
  //private
  var layer = e.target;
  var layerCode = layer.feature.properties[mapCode];
  var color = ['blue', 'blue', 'blue'];

  clearFlows();   

  if (activeLayer != layerCode && $.inArray(layerCode.toLowerCase(), MapGroup) > -1) {
    addLines(
      {
        center: layerCode,
        stars: getFlows(layerCode)
      },
      color[colorIdx],
      centroides,
      mapdataviz
    );
    colorIdx = (colorIdx + 1) % 3;
    activeLayer = layerCode;
    updateFlowLegend (layerCode);

  } else {
    activeLayer = "";
  }
}


function clearFlows () {
  flowGroup.clearLayers().addTo(mapdataviz);  
}



function getFlows(country) {
  var data = window.DataforFlow;
  var idxCountry = data.keys[0].indexOf(country.toUpperCase());
  var newData = {};
  var max = 0;
  $.each(data.keys[1], function (key, value) {
    var crData = data.data[idxCountry][data.keys[1].indexOf(value.toUpperCase())][0];
    if (max < crData) {
      max = crData;
    }
  });
  $.each(data.keys[1], function (key, value) {
    var crData1 = data.data[idxCountry][data.keys[1].indexOf(value.toUpperCase())][0];
    if (crData1 / max > 0.01) {
      newData[value] = getLineSize(crData1, window.maxBubble);
    } else {
      newData[value] = 0;
    }
  });

  function getLineSize(d, max) {
  //private
    if (d == null || d <= 0) return null;
    var width = (Math.sqrt(d / max) * 25);
    return width;
  }

  return newData;

}


function clearFlowLegend () {
  $('#flowLayer .helptext').show();
  $('#flowLayer #detailsTable').hide();
  $('#flowLayer .countryPrefix').text('');
  $('#flowLayer .country').html('');
}



function updateFlowLegend (layerCode) {

  var flowLabels = {
    'tableHead1' : {
      'en':'Main export destinations',
      'cn':'主要出口目的地'
    },
    'tableHead2' : {
      'en':'USD Millions',
      'cn':'百万美元'
    },
    'world' : {
      'en':'World',
      'cn':'世界'
    },
    'unspecified' : {
      'en':'unspecified',
      'cn':'未标明'
    },
    'asia' : {
      'en':'Asia n.e.s.',
      'cn':'亚洲n.e.s.'
    },
    'headlinePrefix' : {
      'en':'Exports from ',
      'cn':'从'
    },
    'headlineSuffix' : {
      'en':'',
      'cn':'的出口'
    },
  }

  if (window.lang_countries[layerCode.toLowerCase()] != null) {
    $('#flowLayer .country').html(window.lang_countries[layerCode.toLowerCase()]);
  } else {
    $('#flowLayer .country').html(layerCode);
  }
  $('#flowLayer #countryPrefix').text(flowLabels.headlinePrefix[lang]);
  $('#flowLayer #countrySuffix').text(flowLabels.headlineSuffix[lang]);
  $('#flowLayer .helptext').hide();
  $('#flowLayer #detailsTable').show();
  $('#flowLayer #detailsTable thead .countries').text(flowLabels.tableHead1[lang]);
  $('#flowLayer #detailsTable thead .values').text(flowLabels.tableHead2[lang]);
  $('#flowLayer #detailsTable tbody').remove();
  $('#flowLayer #detailsTable').append('<tbody>');
  var legendData = getFlowsLegend(layerCode);
  $.each(legendData['rank'], function (key, value) {
    if (window.lang_countries[legendData['values'][value].toLowerCase()] != null) {
      country = window.lang_countries[legendData['values'][value].toLowerCase()];
    } else if (legendData['values'][value] == 'WLD') {
      country = flowLabels.world[lang];
    } else if (legendData['values'][value] == 'UNSPE') {
      country = flowLabels.unspecified[lang];
    } else if (legendData['values'][value] == 'XCD') {
      country = flowLabels.asia[lang];
    } else {
      country = legendData['values'][value];
    }
    $('#flowLayer #detailsTable tbody').append('<tr><td>'+country+'</td><td  class="tableValues">'+Math.round(value/1000000)+'</td>');
  });
  
  function getFlowsLegend(country) {
    var data = window.DataforFlow;
    var idxCountry = data.keys[0].indexOf(country.toUpperCase());
    var newData = {};
    newData['rank'] = [];
    newData['values'] = {};
    var max = 0;
    $.each(data.keys[1], function (key, value) {
      var crData = data.data[idxCountry][data.keys[1].indexOf(value.toUpperCase())][0];
      if (max < crData) {
        max = crData;
      }
    });
    $.each(data.keys[1], function (key, value) {
      var crData1 = data.data[idxCountry][data.keys[1].indexOf(value.toUpperCase())][0];
      if (crData1 / max > 0.01) {
        newData['values'][crData1]=value;
        newData['rank'].push(crData1);
      }
    });
    newData['rank'].sort(function(a, b){return b-a});
    return newData;
  }
}

function addLines(flow, color, centros, mapdataviz) {
  var stars = {};
  $.each(flow.stars, function (country, value) {
    if (centros[country.toUpperCase()] != undefined) {
      stars[country] = {};
      stars[country]['latlong'] = [parseFloat(centros[country.toUpperCase()].Lat), parseFloat(centros[country.toUpperCase()].Long)];
      stars[country]['value'] = value
    }
  });
  var center = [parseFloat(centros[flow.center.toUpperCase()].Lat), parseFloat(centros[flow.center.toUpperCase()].Long)];
  $.each(stars, function (country, values) {
    var lines = L.polyline([center, values.latlong], {
      color: color,
      weight: values.value,
      fill: 'blue'
    });
    flowGroup.addLayer(lines);
    //flowGroup.addLayer(L.polylineDecorator(lines, {
    //  patterns: [{
    //    offset: '100%',
    //    repeat: 0,
    //    symbol: L.Symbol.arrowHead({
    //      pixelSize: values.value*3,
    //      polygon: false,
    //      pathOptions: {stroke: true, weight: values.value}
    //    })
    //  }]
    //}));
  });
}

/*
 function addRectangle(flow,centros,mapdataviz) {
 var stars = {};
 $.each(flow.stars, function (country,value) {
 stars[country] = {};
 stars[country]['latlong'] = [parseFloat(centros[country.toUpperCase()].Lat),parseFloat(centros[country.toUpperCase()].Long)];
 stars[country]['value'] = value
 });
 $.each(stars, function (country, values) {
 L.rectangle([[values.latlong[0],values.latlong[1]],[values.latlong[0]+values.value,values.latlong[1]+0.75]], {
 fillOpacity : 1,
 color: "blue",
 weight: 1
 }).addTo(mapdataviz);
 L.rectangle([[values.latlong[0],values.latlong[1]-0.75],[values.latlong[0]+1,values.latlong[1]]], {
 fillOpacity : 1,
 color: "red",
 weight: 1
 }).addTo(mapdataviz);
 });
 }
 */

