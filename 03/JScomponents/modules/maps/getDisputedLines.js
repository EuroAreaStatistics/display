

//returns lines for disputed territories for a given country group

function getDisputedLines (lines,group) {
  var disputedLinesNew = {
        "type": "FeatureCollection",
        "features": []
        }
  if ($.inArray('ind', group)>-1 || $.inArray('chn', group)>-1 || $.inArray('pak', group)>-1) {
    $.each(lines.features, function (k, feature) {
      if (feature.properties.SOVEREIGNT == 'IndiaChinaPakistan' ) {
        disputedLinesNew['features'].push(feature);
      }
    });
  } if ($.inArray('ind', group)>-1 || $.inArray('chn', group)>-1 ) {
    $.each(lines.features, function (k, feature) {
      if (feature.properties.SOVEREIGNT == 'IndiaChina' ) {
        disputedLinesNew['features'].push(feature);
      }
    });
  } if ($.inArray('egy', group)>-1 || $.inArray('sdn', group)>-1 ) {
    $.each(lines.features, function (k, feature) {
      if (feature.properties.SOVEREIGNT == 'EgyptSudan' ) {
        disputedLinesNew['features'].push(feature);
      }
    });
  } if ($.inArray('sdn', group)>-1 || $.inArray('ssd', group)>-1 ) {
    $.each(lines.features, function (k, feature) {
      if (feature.properties.SOVEREIGNT == 'Sudan' ) {
        disputedLinesNew['features'].push(feature);
      }
    });
  } if ($.inArray('ken', group)>-1 || $.inArray('som', group)>-1 ) {
    $.each(lines.features, function (k, feature) {
      if (feature.properties.SOVEREIGNT == 'KenyaSom' ) {
        disputedLinesNew['features'].push(feature);
      }
    });
  }
  return disputedLinesNew;

}
