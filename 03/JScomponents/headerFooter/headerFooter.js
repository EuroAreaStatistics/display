$(function() {
  $('#tab' + page).addClass('navactive');

  if (navTabs == 0) {
    $('#topicNav').hide();
  }

  $('.navTabs').click(changePage);

  function changePage() {
    var value = $(this).attr('value');
    location = '/' + project + '?cr=' + urlcountry + '&lg=' + lang + '&page=' + value + '&template=' + template;
  }

  $('.btn_screen').click(fullscreen);

  function fullscreen(ev) {
    ev.preventDefault();
    window.open(window.location);
  }

  $('#aboutContainer').hide();
  $('#shareContainer').hide();
  $('#embedContainer').hide();

  $('#aboutLink').click(function(){
    $('#aboutContainer').toggle();
    $('#shareContainer').hide();
  });

  $('#shareLink').click(function(){
    $('#shareContainer').toggle();
    $('#aboutContainer').hide();
  });

// when a second share button is present
  $('#shareLink2').click(function(){
    $('#shareContainer').toggle();
    $('#aboutContainer').hide();
  });

  $('#closeAbout').click(function(){
    $('#aboutContainer').hide();
  });

  $('#closeShare').click(function(){
    $('#shareContainer').hide();
  });

// lang select header
  $('#langSelect').val(lang);

  $('#langSelect').change(function(){
    var value = $(this).attr('value');
    var params = {
      cr: urlcountry,
      lg: lang,
      page: page,
      charts: window.Chart.slice(),
    };
    if (template != null) {
      params.template = template;
    }
    params.lg = value;
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
  });

// template select header
  $('#templateSelect').change(function(){
    var value = $(this).attr('value');
    if (value != 'notSet') {
      var params = {
        cr: urlcountry,
        lg: lang,
        page: page,
        charts: window.Chart.slice()
      };
      params.template = value;
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
    }
  });
});
