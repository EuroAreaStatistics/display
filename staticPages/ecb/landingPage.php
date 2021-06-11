<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../../03/headerFooter');

require (__DIR__.'/../resources/SuperTemplates.php');

if (file_exists(__DIR__.'/langIndex/Index_master_'.$language.'.php')) {
  require(__DIR__.'/langIndex/Index_master_'.$language.'.php');
} else {
  require(__DIR__.'/langIndex/Index_master_en.php');
  $noLocalLanguage = "<p class='chromeframe'>We are sorry, this page is not yet available in your language. Content will be displayed in English.</p>";
}

if (isset($langIndexDefault) && $langIndexDefault != null) {
  $lang = array_replace_recursive ($langIndexDefault, $lang);
}

$lang['page_title'] = $lang['main_title'];

$projects = json_decode(file_get_contents(__DIR__.'/projects.json'), TRUE);
$projects = array_map(function($d) use($projectsWizard) { $d['projects'] = array_intersect($d['projects'], array_keys($projectsWizard)); return $d; }, $projects);
$projects = array_filter($projects, function($d) { return count($d['projects']); });

$firstCountry = array_values(array_intersect(array_keys($lang_countries), $groups['group_EMU']))[0];

$config['languages'] = array_keys($shareLanguages);

$menu = [];
foreach ($projects as $k => $d) {
  $m = isset($lang['projects'][$d['projects'][0] . '_main']) ? $lang['projects'][$d['projects'][0] . '_main'] : $lang[str_replace('-', '_', $d['projects'][0] . '_main')];
  foreach ($d['projects'] as $p) {
    $menu[$m][$lang['projects'][$p]] = "$p?cr=".($p == 'macroeconomic-scoreboard' ? $firstCountry : ($p == 'current-account-by-main-counterparties' ? 'bra' : 'eur') )."&lg=$language";
  }
}
ksort($menu);

if (file_exists(__DIR__."/../../../02projects/ecb/wizard-edit-repo/wizardProjects/lang/digpub-2/lang_$language.json")) {
  $ipub2 = json_decode(file_get_contents(__DIR__."/../../../02projects/ecb/wizard-edit-repo/wizardProjects/lang/digpub-2/lang_$language.json"), TRUE);
  $lang['ipubs_heading'] = strip_tags($ipub2['landingpage.teaser']);
  $lang['ipub2_teaser'] = $ipub2['description'];
} else if (file_exists(__DIR__."/../../digital-publication/statistics-insights-inflation/l10n/$language.json")) {
  $ipub2 = json_decode(file_get_contents(__DIR__."/../../digital-publication/statistics-insights-inflation/l10n/$language.json"), TRUE);
  $lang['ipubs_heading'] = strip_tags($ipub2['landingpage.teaser']);
  $lang['ipub2_teaser'] = $ipub2['description'];
} else {
  $lang['ipubs_heading'] = 'Interactive publications';
  $lang['ipub2_teaser'] = 'Inflation';
}

?>
<!doctype html>
<html lang="<?= $language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $lang['main_title'] ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="<?= $staticURL ?>/img/<?= $themeURL ?>/favicon.png">
    <link href='//fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= $vendorsURL ?>/normalize-css-3.0.3/normalize.css">
    <link rel="stylesheet" href="<?= $liveURL ?>/03resources/css/<?= $themeURL ?>.css">
<meta name="twitter:widgets:csp" content="on">
<meta name="twitter:dnt" content="on">
<link rel="stylesheet" href="<?= $vendorsURL ?>/cookieconsent/build/cookieconsent.min.css">
<script src="<?= $vendorsURL ?>/cookieconsent/build/cookieconsent.min.js"></script>
<script> var lang_labels = <?= json_encode($lang) ?> </script>
<script> var features = <?= json_encode($features) ?> </script>
<script>
window.addEventListener("load", function(){
function onCookies(allowed) {
  if (allowed) {
    if (/(MSIE ([6789]|10|11))|Trident/.test(navigator.userAgent)) {
      $('section > div').last().append('<div class="gray box" id="tweets"><div><a href="https://twitter.com/ecb" target="_blank" rel="noopener noreferrer">'+lang_labels['tweets_by_ecb']+'</a> <p><a href="https://twitter.com/ecb" target="_blank" rel="noopener noreferrer">'+lang_labels['open_twitter']+'</a></p></div></div>');
    } else {
      $('section > div').last().append('<div class="gray box" id="tweets"><div><a href="https://twitter.com/ecb" target="_blank" rel="noopener noreferrer">'+lang_labels['tweets_by_ecb']+'</a> <a class="twitter-timeline" href="https://twitter.com/ecb" data-chrome="noheader nofooter" data-tweet-limit="5" data-dnt="true">[loading]</a></div></div>');
      if (window.twttr && twttr.widgets) {
        twttr.widgets.load(document.getElementById("tweets"));
      } else {
        window.twttr = (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
          if (d.getElementById(id)) return t;
          js = d.createElement(s);
          js.id = id;
          js.src = "https://platform.twitter.com/widgets.js";
          fjs.parentNode.insertBefore(js, fjs);

          t._e = [];
          t.ready = function(f) {
            t._e.push(f);
          };

          return t;
        }(document, "script", "twitter-wjs"));
      }
    }
  } else {
    $('#tweets').remove();
  }
}
window.cookieconsent.initialise($.extend(
{
  "palette": {
    "popup": {
      "background": "#e3e4e5",
      "text": "#707173"
    },
    "button": {
      "background": "#004996",
      "text": "#ffffff"
    }
  },
  "content": {
    "message": $('<div>').html(lang_labels['cookies_message']).text(),
    "dismiss": $('<div>').html(lang_labels['cookies_button']).text(),
    "link": $('<div>').html(lang_labels['cookies_details']).text(),
    "allow": $('<div>').html(lang_labels['cookies_allow']).text(),
    "deny": $('<div>').html(lang_labels['cookies_deny']).text(),
    "policy": $('<div>').html(lang_labels['cookies_policy']).text(),
    "href": "https://help.twitter.com/rules-and-policies/twitter-cookies"
  },
},
!features.cookiesOptIn ? {} : {
  "compliance": {
        'opt-in':
          '<div class="cc-compliance cc-highlight">{{deny}}{{allow}}</div>',
      },
  "type": "opt-in",
        onInitialise: function (status) {
          onCookies(status === 'allow');
        },
        onStatusChange: function (status) {
          onCookies(status === 'allow');
        },
        onRevokeChoice: function () {
          onCookies(false);
        }
}));
onCookies(!features.cookiesOptIn);
});
</script>
<style>
#tweets > div {
  height: 100%;
  overflow: auto;
}
.subHeaderContainer {
  background-color: #e3e4e5;
}
.subHeader {
  box-sizing: border-box;
  font-size: 20px;
  color: #004996;
  padding: 0 50px 10px 50px;
  z-index: 10;
  max-width: 1600px;
  margin: auto;
}
.subHeader a {
  text-decoration: underline;
  color: inherit;
}
.indicatorHeader {
  box-sizing: border-box;
  width: 100% !important;
  padding-top: 16px !important;
  padding-bottom: 16px !important;
  padding-right: 10px !important;
  line-height: normal !important;
  color: #707173;
  font-size: 16px;
}
header {
  margin-bottom: 0;
}

/* Desktop - 1599px - 1024px */
@media only screen and (max-width: 1599px) {
  .subHeader {
    font-size: 18px;
  }
}


/* Smartphone - 1023px and below */
@media only screen and (max-width: 1023px) {
  .subHeader {
    font-size: 14px;
    padding: 0 10px 10px 10px;
  }
}

/* tweets */
section .box.gray {
  height: 600px;
}

/* blog */
section .box.lightblue.blog::before {
  background-color: #e4ab28;
}

/* digital publicaiton */
section .box.lightblue.se::before {
  background-color: #0d4da1;
}
section .box.lightblue.se p {
  margin: 0;
}

/* video */
section .box.lightblue.video::before {
  background-color: #000000;
}


/* three column layout */
@media only screen and (min-width: 1600px) {
  section .column {
    width: 39%;
  }

  section > .left-hand.column {
    margin-right: 25px;
  }

  section .box.green {
    display: block;
    padding-top: 7px;
  }

  section .box.green .column {
    width: 100%;
    margin: 0;
    border: none;
  }

  section .box.green .column > span.empty {
    display: none;
  }

  section > .right-hand.column {
    width: 50%;
    -ms-flex-item-align: start;
    align-self: flex-start;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
  }

  .two-columns {
    width: 48%;
    margin-right: 25px;
  }

  section .box.gray {
    height: auto;
    -ms-flex-item-align: stretch;
    align-self: stretch;
    width: 48%;
  }
}

p.blog-title {
  margin-top: 0;
  margin-bottom: 5px;
}
div.blog-box {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}
div.blog-image {
  -ms-flex: 1 0 300px;
  flex: 1 0 300px;
}
.blog-image img {
  width: 100%;
}
div.blog-excerpt {
  -ms-flex: 1 1 300px;
  flex: 1 1 300px;
  padding-left: 10px;
  padding-right: 10px;
}
p.blog-date {
  padding-bottom: 10px;
}

</style>
</head>
<body>
    <header>
        <div class="container">
            <a class="logo" href="#">
                <img src="<?= $staticURL ?>/img/ecb/ecb_our_statistics_<?= $language ?>.svg" alt="">
            </a>
            <a class="share" title="Share"><img src="<?= $staticURL ?>/img/share.png" alt=""></a>
            <div class="share">
                <a class="close"></a>
                <a href="#" onClick="Popup=window.open('https://twitter.com/intent/tweet?text=Euro area statistics&amp;url=https%3A%2F%2Fwww.euro-area-statistics.org/', 'Popup', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=626,height=436,left=100,top=50'); return false;" title="<?= $lang['aboutTwitter'] ?>"><img src="<?= $staticURL ?>/img/twitter.svg" alt=""></a>
                <a href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 'facebook', 'width=626,height=436'); return false;" title="<?= $lang['aboutFacebook'] ?>"><img src="<?= $staticURL ?>/img/facebook.svg" alt=""></a>
                <a href="mailto:?subject=Euro%20area%20statistics&amp;body=Check%20out%20this%20site:%20https://www.euro-area-statistics.org/" title="<?= htmlspecialchars($lang['aboutEmail']) ?>"><img src="<?= $staticURL ?>/img/mail.svg" alt=""></a>
                <a href="#" onClick="Popup=window.open('embed?project=&amp;lg='+$('html').attr('lang')+'&amp;cr=eur&amp;page=0', 'Popup', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1140,height=750,left=100,top=50'); return false;" title="<?= $lang['aboutEmbed'] ?>"><img src="<?= $staticURL ?>/img/embed.svg" alt=""></a>
<?php if (FALSE): ?>
                <a href="#" title="Subscribe"><img src="<?= $staticURL ?>/img/subscribe.svg" alt=""></a>
<?php endif ?>
            </div>
            <a class="info" title="Info"><img src="<?= $staticURL ?>/img/info.svg" alt=""></a>
            <div class="language dropdown">
                <span><?= $shareLanguages[$language] ?></span>
<?php foreach ($shareLanguages as $code => $name) : ?>
<?php   if ($code === $language) continue; ?>
                <span data-code="<?= htmlspecialchars($code) ?>"><?= $name ?></span>
<?php endforeach ?>
            </div>
        </div>
    </header>
    <div class="subHeaderContainer"><div class="subHeader"><?= $lang['teaser_landing_page1'] ?></div></div>
    <section>
      <div class="left-hand column">
        <a href="/blog/dont-take-it-for-granted-the-value-of-data-and-statistics-for-the-ecbs-policy-making?lang=en">
          <div class="lightblue box blog">
             <div class="blog-box">
               <div class="blog-image">
                  <img src="<?= $staticURL ?>/img/ecb/Isabel-Schnabel-Portrait_300x190.jpg">
               </div>
               <div class="blog-excerpt">
                  <h3>Don’t take it for granted: the value of high-quality data and statistics for the ECB’s policymaking</h3>
                  <p class="blog-date">20 October 2020<p>
                  <p>Institutions all over the world are today celebrating the third World Statistics Day with the theme “Connecting the world with data we can trust”.</p>
                </div>
             </div>
          </div>
        </a>
        <div class="green box">
<?php for ($i = 0; $i < 2; $i++): ?>
	  <div class="column">
<?php   if ($i == 0): ?>
            <span class="indicatorHeader"><?= $lang['teaser_landing_page2'] ?></span>
<?php   else: ?>
            <span class="empty">&nbsp;</span>
<?php   endif ?>
            <ul class="indicators">
<?php   foreach (array_slice(array_keys($menu), $i*(count(array_keys($menu))+1)/2, (count(array_keys($menu))+1)/2) as $m): ?>
              <li>
                <a href="#"><?= htmlspecialchars($m) ?></a>
                <ul>
<?php     foreach ($menu[$m] as $s => $l): ?>
                  <li><a href="<?= htmlspecialchars($l) ?>"><?= htmlspecialchars($s) ?></a></li>
<?php     endforeach ?>
                </ul>
              </li>
<?php   endforeach ?>
            </ul>
          </div>
<?php endfor ?>
        </div>
        <div style="margin:auto"><a href="https://op.europa.eu/en/web/euopendatadays" title="EU Open Data Days 2021"><img src="<?= $staticURL ?>/img/ecb/button-odd-2021.png" style="max-width:100%;margin:auto;padding-top:30px"></a></div>
      </div>
      <div class="right-hand column">
<div class="two-columns">
            <div class="lightblue box">
                <a href="/statistics-insights/the-euro-short-term-rate-str-is-the-euro-overnight-money-market-interest-rate?lg=<?= $language ?>">
		    <?= $lang['insights_page_title'] ?>
                    <h2><?= $lang['projects']['the-euro-short-term-rate-str-is-the-euro-overnight-money-market-interest-rate'] ?></h2>
                    <div class="image">
                        <img src="/statistics-insights/downloads/estr-768x323.png" style="padding-bottom:5px" alt="">
                    </div>
                    <?= $lang['projects']['the-euro-short-term-rate-str-is-the-euro-overnight-money-market-interest-rate_highlight'] ?>
                </a>
            </div>
            <div class="lightblue box se" style="padding-bottom:5px">
               <h2><img src="<?= $staticURL ?>/img/ecb/pub.png" style="text-align:center;height:2.15em;padding-right:.6em"><?= $lang['ipubs_heading'] ?></h2>
<table><tr><td colspan=2 style="padding-bottom:5px">
               <hr style="margin:0" />
</td></tr><tr><td style="padding-right:15px">
                <a href="/digital-publication/statistics-insights-inflation/?lang=<?= $language ?>">
                        <img src="/digital-publication/statistics-insights-inflation/images/cover-small.jpg" style="padding-bottom:5px;width:80px;height:auto" alt="">
                </a>
</td><td style="vertical-align:top">
                <a href="/digital-publication/statistics-insights-inflation/?lang=<?= $language ?>">
		        <?= $lang['ipub2_teaser'] ?>
                </a>
</td></tr><tr><td colspan=2 style="padding-bottom:5px">
               <hr style="margin:0" />
</td></tr><tr><td style="padding-right:15px">
                <a href="/digital-publication/statistics-insights-money-credit-and-central-bank-interest-rates/?lang=<?= $language ?>">
                        <img src="<?= $staticURL ?>/img/ecb/button-money-and-credit.jpg" style="padding-bottom:5px;width:80px;height:auto" alt="">
                </a>
</td><td style="vertical-align:top">
                <a href="/digital-publication/statistics-insights-money-credit-and-central-bank-interest-rates/?lang=<?= $language ?>">
		        <?= strip_tags(explode("\n", $lang['projects']['digpub-1'])[1]) ?>
                </a>
</td></tr></table>
            </div>
            <div class="red box">
<img src="<?= $staticURL ?>/img/ecb/ncb.svg" style="height:2.15em;float:left;padding-right:.6em">
                <?= $lang['banks_corner_title'] ?>
                <h2><a href="banks-corner?lg=<?= $language ?>"><?= $lang['download_area'] ?></a></h2>
            </div>
</div>
      </div>

      <a href="#" class="top"><img src="<?= $staticURL ?>/img/up.svg" alt="To the top"></a>
    </section>

<?php require('about.php'); ?>
    <script src="<?= $vendorsURL ?>/jquery-3.2.1/dist/jquery.slim.min.js"></script>
    <script src="<?= $staticURL ?>/libs/main.js"></script>
<?php require(__DIR__.'/../../03/analytics/'.$themeURL.'.php'); ?>
</body>
</html>
