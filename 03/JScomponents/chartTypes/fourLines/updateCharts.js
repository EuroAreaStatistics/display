function updateChart(index) {
  return function() {
    var params = {
      cr: urlcountries,
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
      if ($.isArray(v)) {
        url += v.map(encodeURIComponent).join('+');
      } else {
        url += encodeURIComponent(v);
      }
      s = '&';
    });
    location = url;
  };
}
