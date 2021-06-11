function getIndicatorColorScale(d, dMin, dMax, feature, colors) {
  if ($.inArray(feature.properties[mapCode].toString().toLowerCase(), MapGroup) > -1) {
    return quantize(d, dMin, dMax, colors)
  } else {
    return landmass;
  }

}

function quantize(d, dMin, dMax, range) {
  function scaleToInteger(d, dMin, dMax, scaleMin, scaleMax) {
    var i = Math.floor((d - dMin) / (dMax - dMin) * (scaleMax - scaleMin + 1)) + scaleMin;
    if (i < scaleMin) return scaleMin;
    if (i > scaleMax) return scaleMax;
    return i;
  }

  return range[scaleToInteger(d, dMin, dMax, 0, range.length - 1)];
}
