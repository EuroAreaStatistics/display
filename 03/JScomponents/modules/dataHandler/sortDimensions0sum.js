// sorts first dimension on sum of keys of second dimension
function sortDimension0sum(d, desc) {
  function add(a, b) {
    return a == null ? b
         : b == null ? a
         : a + b;
  }
  desc = desc || true;
  var r = {dimensions: d.dimensions, keys: d.keys.slice(0)};
  var sorted = d.data.map(function(v, i) {
    return {v: v, i: i};
  });
  sorted.sort(function(a, b) {
    return (a.v.reduce(add, 0) == b.v.reduce(add, 0)) ? 0
            : (a.v.reduce(add, 0) < b.v.reduce(add, 0)) != desc ? -1
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
