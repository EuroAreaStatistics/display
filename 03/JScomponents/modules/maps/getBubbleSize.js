

function getBubbleSize(d, dMax, feature) {
  //private
  if (d == null || d <= 0) return null;
  var radius = Math.sqrt(d / dMax) * 25;
  if (radius < 3) radius = 3;
  return radius;
}
