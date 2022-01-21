function highlightAndPopup(feature, layer) {
  if (!(feature && feature.properties && feature.properties[mapCode])) return;
  // public
  var interaction;
  if (window.flowStatus == "flow") {
    // see module getFlows
    interaction = clickFlow;
  } else {
    interaction = highlightAndPopupClick;
  }
  var inEuroArea =
    feature &&
    feature.properties &&
    $.inArray(feature.properties[mapCode].toString().toLowerCase(), [
      "aut",
      "bel",
      "cyp",
      "deu",
      "esp",
      "est",
      "fin",
      "fra",
      "grc",
      "irl",
      "ita",
      "ltu",
      "lux",
      "lva",
      "mlt",
      "nld",
      "prt",
      "svk",
      "svn",
    ]) > -1;
  if (
    (themeURL == "ecb" && !inEuroArea) ||
    $.inArray(
      layer.feature.properties[mapCode].toString().toLowerCase(),
      MapGroup
    ) > -1
  ) {
    layer.on({
      click: interaction,

      mouseover: function () {
        if (!layer.inFront) {
          layer.inFront = true;
          layer
            .setStyle({
              fillOpacity: 0.8,
              weight: 2,
              color:
                bubbleMap != null
                  ? bubbleHighlightBorder
                  : shapeHighlightBorder,
            })
            .bringToFront();
        }
      },
      mouseout: function () {
        layer.inFront = false;
        layer.setStyle({
          fillOpacity: 1,
          weight: landmassBorderWidth,
          color: bubbleMap != null ? bubbleDefaultBorder : landmassBorder,
        });
        if (typeof disputedLines !== "undefined") {
          disputedLines.bringToFront();
        }
        if (typeof flowGroup !== "undefined") {
          flowGroup.eachLayer(function (layer) {
            if (layer.bringToFront) {
              layer.bringToFront();
            } else {
              layer.eachLayer(function (sublayer) {
                sublayer.bringToFront();
              });
            }
          });
        }
      },
    });
    // make layers clickable
    if (layer.eachLayer) {
      layer.eachLayer(function (sublayer) {
        L.Util.setOptions(sublayer, { clickable: true });
      });
    } else {
      L.Util.setOptions(layer, { clickable: true });
    }
  }
}

// show popup on map while closing previous popup
// disposes mobx autorun function before creating new popup
var highlightAndPopupClick = (function (disposer) {
  return function highlightAndPopupClick(e) {
    // private
    var layer = e.target;

    if (disposer) {
      console.log("dispose click layer");
      disposer();
    }
    disposer = mobx.autorun(
      function () {
        console.log("click layer", !!popup._map);
        var specialTooltip =
          window.wizardConfig.tabs[wizardConfig.project.tabs[page]]
            .specialTooltip;
        var specialTooltipLinks =
          window.wizardConfig.tabs[wizardConfig.project.tabs[page]]
            .specialTooltipLinks;
        var teaserText;
        if (specialTooltip == "export-restrictions-main") {
          teaserText = mapTeaserExportRestrictionsMain(
            layer.feature.properties[mapCode]
          );
        } else if (specialTooltip == "export-restrictions") {
          teaserText = mapTeaserExportRestrictions(
            layer.feature.properties[mapCode]
          );
        } else if (specialTooltip == "ifi-country-data") {
          teaserText = mapTeaserIfiCountryData(
            layer.feature.properties[mapCode],
            specialTooltipLinks
          );
        } else if (window.bubbleMap == "multiColored") {
          teaserText = mapTeaserTextMulti(layer.feature.properties[mapCode]);
        } else {
          teaserText = mapTeaserText(layer.feature.properties[mapCode]);
        }
        if (
          themeURL == "ecb" ||
          $.inArray(
            layer.feature.properties[mapCode].toString().toLowerCase(),
            MapGroup
          ) > -1
        ) {
          popup.setContent(teaserText);
        }
      },
      { name: "map.click" }
    );

    if (
      themeURL == "ecb" ||
      $.inArray(
        layer.feature.properties[mapCode].toString().toLowerCase(),
        MapGroup
      ) > -1
    ) {
      popup.setLatLng(e.latlng);
      popup.openOn(mapdataviz);
    }
  };
})();
