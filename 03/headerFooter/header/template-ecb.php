<script>
$(function () {

  // language dropdown -- first child is visible, new language will become first child
  $('#langSelect2').on('click', 'span', function() {
    $(this).parent().toggleClass('open');
    var value = $(this).attr('data-code');
    if (value != null) {
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
    }
  });

  // template dropdown -- first child is visible, new template will become first child
  $('#templateSelect2 span[data-code="'+template+'"]').attr('data-code',null).each(function() { $(this).prependTo($(this).closest('div')); });
  $('#templateSelect2').on('click', 'span', function() {
    $(this).parent().toggleClass('open');
    var value = $(this).attr('data-code');
    if (value != null) {
      var params = {
        cr: urlcountry,
        lg: lang,
        page: page,
        charts: window.Chart.slice()
      };
      if (embed) {
        params.embed = embed;
      }
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

  // share dropdown for smartphones
  $('header a.share, header div.share .close').on('click', function() {
    $('header div.share').toggleClass('open');
  });

  // overlay
  $('header .info, aside.overlay .close').on('click', function() {
    $('aside.overlay').toggle();
  });

  // tabs
  $('li[data-tab="'+page+'"]').addClass('active');
  $('li[data-tab]').click(function () {
    if ($(this).hasClass('active')) return;
    var value = $(this).attr('data-tab');
    location = '/' + project + '?cr=' + urlcountry + '&lg=' + lang + '&page=' + value + '&template=' + template;
  });

  // indicator select
  $('select[id^="DataSelect"]').each(function () {
    var $select = $(this);
    $select.hide();
    var dropdown = $('<div class="dropdown gray">');
    $select.find('option').each(function () {
      if ($(this).prop('selected')) {
        $('<span>')
          .text($(this).text())
          .attr('title', ($(this).text()))
          .prependTo(dropdown);
      } else {
        $('<span>')
          .text($(this).text())
          .attr('title', ($(this).text()))
          .attr('data-code', $(this).val())
          .appendTo(dropdown);
      }
    });
    $select.after(dropdown);
    dropdown.on('click', 'span', function() {
      $(this).parent().toggleClass('open');
      var value = $(this).attr('data-code');
      if (value != null) {
        $select.val(value);
        $select.change();
      }
    });
  });

});
</script>

<?php if (!$this->embed): ?>
<header>
        <div class="container">
            <a class="logo" href="<?= htmlspecialchars($this->home) ?>">
                <img src="<?= $this->staticURL ?>/img/ecb/ecb_our_statistics_<?= $this->language ?>.svg" alt="">
            </a>
            <a class="share" title="Share"><img src="<?= $this->staticURL ?>/img/share.png" alt=""></a>
            <div class="share">
                <a class="close"></a>
                <a href="#" onClick="Popup=window.open('https://twitter.com/intent/tweet?text=Euro area statistics&amp;url=https%3A%2F%2Fwww.euro-area-statistics.org/<?= $this->project ?>', 'Popup', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=626,height=436,left=100,top=50'); return false;" title="<?= $this->lang['aboutTwitter'] ?>"><img src="<?= $this->staticURL ?>/img/twitter.svg" alt=""></a>
                <a href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 'facebook', 'width=626,height=436'); return false;" title="<?= $this->lang['aboutFacebook'] ?>"><img src="<?= $this->staticURL ?>/img/facebook.svg" alt=""></a>
                <a href="mailto:?subject=Euro%20area%20statistics&amp;body=Check%20out%20this%20site:%20https://www.euro-area-statistics.org/<?= $this->project ?>" title="<?= htmlspecialchars($this->lang['aboutEmail']) ?>"><img src="<?= $this->staticURL ?>/img/mail.svg" alt=""></a>
                <a href="#" onClick="Popup=window.open(
				baseURL+'/embed?project='+project+'&amp;lg='+window.lang+'&amp;cr='+urlcountry+'&amp;page='+page+'',
				'Popup',
				'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1140,height=750,left=100,top=50'); return false;" title="<?= $this->lang['aboutEmbed'] ?>"><img src="<?= $this->staticURL ?>/img/embed.svg" alt=""></a>
<?php if (FALSE): ?>
                <a href="#" title="Subscribe"><img src="<?= $this->staticURL ?>/img/subscribe.svg" alt=""></a>
<?php endif ?>
            </div>
            <a id="aboutLink" class="info" title="Info"><img src="<?= $this->staticURL ?>/img/info.svg" alt=""></a>
<?php if ($this->languages) : ?>
            <div class="language dropdown" id="langSelect2">
                <span><?= $this->languages[$this->language] ?></span>
<?php   foreach ($this->languages as $code => $name) : ?>
<?php     if ($code === $this->language) continue; ?>
                <span data-code="<?= htmlspecialchars($code) ?>"><?= $name ?></span>
<?php   endforeach ?>
            </div>
<?php endif ?>
        </div>
</header>
<?php endif ?>

<section class="content <?= $this->embed ? "embed embed".$this->embed : "" ?>">
    <div class="<?= $this->embed ? "box embed embed".$this->embed : "green box"?>">
        <h2><?= strip_tags($this->pageTitle) ?></h2>
<?php if ($this->templates && $this->embed != 2 && $this->embed != 3) : ?>
        <div class="dropdown" id="templateSelect2">
<?php   foreach ($this->templates as $code => $name) : ?>
            <span data-code="<?= htmlspecialchars($code) ?>"><?= $name ?></span>
<?php   endforeach ?>
        </div>
<?php endif ?>
