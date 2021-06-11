


//adds small islands as dots on color coded maps

function getMapSubPoints(mapCentroides) {
  if (themeURL == 'ecb') return;
  var mapSubPoints = {
        "type": "FeatureCollection",
        "features": []
        }
  var smallIslands = ['AND','ATG','AIA','ASM','ABW','ALA','BRB','BLM','BMU','BVT','CCK','COK','CPV','CUW','CXR','DMA','FLK','FSM','GRD','GGY','GIB','GLP','SGS','GUM','HKG','IMN','JEY','KIR','COM','KNA','CYM','LCA','LIE','MCO','MAF','MHL','MAC','MNP','MTQ','MSR','MLT','MUS','MDV','NFK','NRU','NIU','PYF','SPM','PCN','PRI','PLW','REU','SYC','SHN','SJM','SMR','STP','SXM','TCA','ATF','TKL','TON','TTO','TUV','VAT','VCT','VGB','VIR','WLF','WSM','MYT','ANT'];
  $(mapCentroides.features).each(function(feature){
    if ($.inArray(this.properties[mapCode], smallIslands) > -1) {
      mapSubPoints['features'].push(this);
    }
  });
  return mapSubPoints;
}
