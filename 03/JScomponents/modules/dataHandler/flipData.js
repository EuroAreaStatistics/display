

//flips dimensions on two dimensional data model
function flipData(d) {
    var r = {keys: d.keys.slice(0), dimensions: d.dimensions.slice(0)};
    r.keys.reverse();
    r.dimensions.reverse();
    r.data = r.keys[0].map(function(k, i0) {
        return r.keys[1].map(function(k, i1) {
            return d.data[i1][i0];
        });
    });
    return r;
}
