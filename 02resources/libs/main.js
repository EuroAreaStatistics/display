$(function(){
  // indicator dropdowns
  $('ul.indicators').on('click','> li > a', function() {
    $(this).parent().toggleClass('active');
    return false;
  });

  // overlay
  $('header .info, aside.overlay .close').on('click', function(ev) {
    ev.preventDefault();
    $('aside.overlay').toggle();
  });

  // language dropdown -- first child is visible, new language will become first child
  $('.dropdown').on('click', 'span', function() {
    $(this).parent().toggleClass('open');
    var value = $(this).attr('data-code');
    if (value != null) {
      var urlcountry;
      var matches = window.location.href.match(/.*[?&]cr=([^&]+)/);
      urlcountry = (matches != null) ? matches[1] : 'eur';
      var page = 0;
      location = location.pathname + '?cr=' + urlcountry + '&lg=' + value + '&page=' + page + '';
    }
  });

  // share dropdown for smartphones
  $('header a.share, header div.share .close').on('click', function() {
    $('header div.share').toggleClass('open');
  });

  // gray box paging
  $('.box.gray').on('click', '.before, .after',function(event) {
    if($(event.target).hasClass('before')) {
      $('.box.gray ul li:last-child').prependTo($('.box.gray ul'));
    } else {
      $('.box.gray ul li:first-child').appendTo($('.box.gray ul'));
    }
  });

  // tabs
  $('.tabs').on('click', 'li', function() {
    if ($(this).hasClass('active')) return;
    $(this).addClass('active').siblings('.active').removeClass('active');
    var content = $(this).closest('.tabs').next('.tabcontent').find('li').get($(this).index());
    $(content).addClass('active').siblings('.active').removeClass('active');
  });
});
