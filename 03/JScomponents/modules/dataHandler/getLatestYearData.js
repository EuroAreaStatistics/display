


// returns the latest available year for a two dimensional JSON data set

function getLatestYearData (data) {
  var newData = {};
  var latestYear = data.keys[1].length-1;
  $.each(data.keys[0], function (key,value) {
      newData[value]=[];
      while (data.data[data.keys[0].indexOf(value)][latestYear] == null && latestYear>=0) {
          latestYear = latestYear-1;
      };
      newData[value][0]=data.keys[1][latestYear];
      newData[value][1]=data.data[data.keys[0].indexOf(value)][latestYear];
      latestYear = data.keys[1].length-1;
  });
  return newData;
}
