var landmass;
var landmassBorder;
var landmassBorderWidth;
var landmassGroup;
var bubbleDefaultBorder;
var bubbleDefaultColor;
var bubbleHighlightBorder;
var shapeHighlightBorder;
if (themeURL=='ecb') {
  landmass = '#f0f0f0';
  landmassNA = '#fff';
  landmassBorder = '#000';
  landmassBorderWidth = 0.5;
  landmassGroup = '#f0f0f0';
  bubbleDefaultBorder = "#494444";
  bubbleDefaultColor = "#d56620";
  bubbleHighlightBorder = "#fff";
  shapeHighlightBorder = '#fff';
} else {
  landmass = '#939393';
  landmassNA = '#939393';
  landmassBorder = '#939393';
  landmassBorderWidth = 1;
  landmassGroup = '#BBBBBB';
  bubbleDefaultBorder = "#494444";
  bubbleDefaultColor = "#d56620";
  bubbleHighlightBorder = "#fff";
  shapeHighlightBorder = '#fff';
}

if (project == 'trade-in-raw-materials') {
  landmass = '#BBBBBB';
  landmassNA = '#BBBBBB';
}

if (project == 'itf-test') {
  landmass = '#BBBBBB';
  landmassNA = '#BBBBBB';
}

function getColorCountryShapesStyleGeneric(layerData) {
  //public
  return function (feature) {

    if (feature && feature.properties && layerData['values'][feature.properties[mapCode]] != null) {
      return {
        weight: landmassBorderWidth,
        opacity: 1,
        color: landmassBorder,
        fillOpacity: 1,
        fillColor: getIndicatorColorScale(layerData['values'][feature.properties[mapCode]][1], layerData['min'], layerData['max'], feature, layerData['colors']) || landmassNA,
        className: "hasValue"
      };
    } else if (feature && feature.properties && $.inArray(feature.properties[mapCode].toString().toLowerCase(), MapGroup) > -1) {
      return {
        weight: landmassBorderWidth,
        opacity: 1,
        color: landmassBorder,
        fillOpacity: 1,
        fillColor: landmassNA,
        className: "hasValue"
      };
    } else if (feature && feature.properties && $.inArray(feature.properties[mapCode].toString().toLowerCase(), ["aut", "bel", "cyp", "deu", "esp", "est", "fin", "fra", "grc", "irl", "ita", "ltu", "lux", "lva", "mlt", "nld", "prt", "svk", "svn"]) > -1) {
      return {
        weight: landmassBorderWidth,
        opacity: 1,
        color: landmassBorder,
        fillOpacity: 1,
        fillColor: '#004996',
        className: "hasValue"
      };
    } else if (feature && feature.properties && feature.properties[mapCode]) {
      return {
        weight: landmassBorderWidth,
        opacity: 1,
        color: landmassBorder,
        fillOpacity: 1,
        fillColor: landmass,
        className: "hasValue"
      };
    } else {
      return {
        weight: landmassBorderWidth,
        opacity: 1,
        color: landmassBorder,
        fillOpacity: 1,
        fillColor: landmassGroup,
        className: "hasValue"
      };
    }

  };

}


function bubblesSimpleGeneric (Data) {
  return function (feature, latlng) {
    //public
    return L.circleMarker(latlng, getBubbleStyleGeneric(feature, Data));
  };
}

function bubblesSimpleColorGeneric (colorData) {
  return function (feature, latlng) {
    //public
    return L.circleMarker(latlng, getBubbleColorGeneric(feature,colorData));
  };
}

function bubblesSizeColorGeneric (sizeData,colorData) {
  return function (feature, latlng) {
    //public
    return L.circleMarker(latlng, getBubbleSizeColorGeneric(feature,sizeData,colorData));
  };
}



function getBubbleStyleGeneric(feature, layerData) {
  //private
    var bubbleColor;
    if ($.inArray(feature.properties[mapCode].toString().toLowerCase(), MapGroup) > -1 && layerData['values'][feature.properties[mapCode]] != null) {
        bubbleColor = bubbleDefaultColor;
      if (layerData['values'][feature.properties[mapCode]] != null) {
        var radius = getBubbleSize(layerData['values'][feature.properties[mapCode]][1], layerData['max'], feature);
        if (radius != null) {
          return {
            radius: radius,
            fillColor: bubbleColor,
            color: bubbleDefaultBorder,
            weight: 1,
            opacity: 1,
            fillOpacity: 1
          };
        }
      }
    }
    return {
      radius: 0,
      opacity: 0
    };
}


function getBubbleSizeColorGeneric(feature,sizeData,colorData) {
  //private
    var bubbleColor;
    if ($.inArray(feature.properties[mapCode].toString().toLowerCase(), MapGroup) > -1 && sizeData['values'][feature.properties[mapCode]] != null) {
      radius = getBubbleSize(sizeData['values'][feature.properties[mapCode]][1], sizeData['max'], feature);
      if (colorData != null) {
        if (colorData['values'][feature.properties[mapCode]] != null) {
          var bubbleColor = getIndicatorColorScale(colorData['values'][feature.properties[mapCode]][1], colorData['min'], colorData['max'],feature, colorData['colors']);
          if (radius != null) {
            return {
              radius: radius,
              fillColor: bubbleColor,
              color: bubbleDefaultBorder,
              weight: 1,
              opacity: 1,
              fillOpacity: 1
            };
          }
        }
      } else {
        if (radius != null) {
          return {
            radius: radius,
            fillColor: bubbleDefaultBorder,
            color: bubbleDefaultBorder,
            weight: 1,
            opacity: 1,
            fillOpacity: 1
          };
        }
      }
    }
    return {
      radius: 0,
      opacity: 0
    };
}

function getBubbleColorGeneric(feature,colorData) {
  //private
    var bubbleColor;
    if ($.inArray(feature.properties[mapCode].toString().toLowerCase(), MapGroup) > -1) {
        radius = 5;
      if (colorData['values'][feature.properties[mapCode]] != null) {
        var bubbleColor = getIndicatorColorScale(colorData['values'][feature.properties[mapCode]][1], colorData['min'], colorData['max'],feature, colorData['colors']);
        if (radius != null) {
          return {
            radius: radius,
            fillColor: bubbleColor,
            color: bubbleDefaultBorder,
            weight: 1,
            opacity: 1,
            fillOpacity: 1
          };
        }
      }
    }
    return {
      radius: 0,
      opacity: 0
    };
}


function getBasicCountryShapesStyle(feature) {
  //private
  function getBasicGroupColor(feature) {
    if ($.inArray(feature.properties[mapCode].toString().toLowerCase(), MapGroup) > -1) {
      return landmassGroup;
    } else {
      return landmass;
    }
  }
  return {
    weight: landmassBorderWidth,
    opacity: 1,
    color: landmassBorder,
    fillOpacity: 1,
    fillColor: getBasicGroupColor(feature)
  };
}





