// sorts first dimension on key of second dimension
function sortDimension0(d, key1, desc) {
  desc = desc || true;
  var r = {dimensions: d.dimensions, keys: d.keys.slice(0)};
  var sorted = d.data.map(function(v, i) {
    return {v: v, i: i};
  });
  sorted.sort(function(a, b) {
    return (a.v[key1] == b.v[key1]) ? 0
            : (a.v[key1] == null) ? 1
            : (b.v[key1] == null) ? -1
            : (a.v[key1] < b.v[key1]) != desc ? -1
            : 1;
  });
  r.data = sorted.map(function(v) {
    return v.v;
  });
  r.keys[0] = sorted.map(function(v) {
    return d.keys[0][v.i];
  });
  return r;
}
