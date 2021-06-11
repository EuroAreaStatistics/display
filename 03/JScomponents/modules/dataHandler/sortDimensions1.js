// sorts second dimension on key of first dimension
function sortDimension1(d, key0, desc) {
  desc = desc || true;
  var r = {dimensions: d.dimensions, keys: d.keys.slice(0)};
  var sorted = d.data[key0].map(function(v, i) {
    return {v: v, i: i};
  });
  sorted.sort(function(a, b) {
    return (a.v == b.v) ? 0
           : (a.v == null) ? 1
           : (b.v == null) ? -1
           : (a.v < b.v) != desc ? -1
           : 1;
  });
  r.data = d.data.map(function(l) {
    return sorted.map(function(v) {
      return l[v.i];
    });
  });
  r.keys[1] = sorted.map(function(v) {
    return d.keys[1][v.i];
  });
  return r;
}
