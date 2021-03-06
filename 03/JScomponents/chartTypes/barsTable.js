$.tablesorter.formatInt = function(s) {
  var i = parseInt(s);
  return (isNaN(i)) ? null : i;
};
$.tablesorter.formatFloat = function(s) {
  var i = parseFloat(s);
  return (isNaN(i)) ? null : i;
};

function updateChart(index) {
  return function() {
    var params = {
      cr: urlcountry,
      lg: lang,
      page: page,
      charts: window.Chart.slice()
    };
    if (embed) {
      params.embed = 1;
    }
    if (template != null) {
      params.template = template;
    }
    params.charts[index] = $(this).val();
    var url = '/' + project;
    var s = '?';
    $.each(params, function(k, v) {
      url += s + encodeURIComponent(k) + '=';
      s = '&';
      if ($.isArray(v)) {
        url += v.map(encodeURIComponent).join('+');
      } else {
        url += encodeURIComponent(v);
      }
    });
    location = url;
  };
}

$(function() {
  var columns = $("#results thead tr > th").length;
  $( document ).tooltip({ tooltipClass: "custom-tooltip-styling" });

  $.each(window.Chart, function(k, v){
    $('#DataSelect' + k).change(updateChart(k));
  });
});
