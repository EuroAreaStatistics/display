'use strict';

$(function() {
  model.height = sizes[0].height;
  model.width = sizes[0].width;

  $('title').text(title);

  $('#select_size')
    .append(sizes.map(function(v, i) {
      return $('<option>')
        .text(v.width + ' x ' + v.height + ' pixel')
        .val(i);
    }))
    .append($('<option>').text('custom size').val('custom'))
    .val(0);

  $('#select_default_page')
    .append(wizardConfig.project.tabs.map(function(v, i) {
      return $('<option>')
        .text(wizardConfig.tabs[v].title.en)
        .val(i);
    }))
    .val(model.page);

  $('#select_default_country')
    .append(order_countries.filter(function(v) {
      return group.indexOf(v) != -1;
    }).map(function(v) {
      return $('<option>')
        .text(lang_countries[v])
        .val(v);
    }))
    .val(model.default_country);

  $('#select_lang')
    .append(order_languages.filter(function(v) {
      return embedLanguages.indexOf(v) != -1;
    }).map(function(v) {
      return $('<option>')
        .text(lang_languages[v])
        .val(v);
    }))
    .val(model.lang);

  updateSelects();
  updateIframe();

  $('#sharetable select').change(updateSettings);
  $('#custom-size-go').click(updateSettings);
  $('#generate-embed').click(generateEmbedLink);
  $('.embedSwitch a').click(embedSwitch);
});

function embedSwitch(e) {
  e.preventDefault();
  location = encodeParams('/embed/', {
    project: project,
    cr: model.default_country,
    lg: model.lang,
    page: model.page
  });
}

function updateIframe() {
  var windowWidth = "innerWidth" in window
                 ? window.innerWidth
                 : document.documentElement.offsetWidth;
  if (model.embedLink == null) {
    $('#codeItem').hide();
    $('#generate-embed').show();
    $('#sharecode').val('');
  } else {
    $('#sharecode').val(generateIframeCode(embedURL));
    $('#codeItem').show();
    $('#generate-embed').hide();
  }
  $('#canvas-wrapper').html(generateIframeCode(baseURL));
  $('#canvas-wrapper iframe').css('margin', '0 auto').css('display', 'block');
}

function updateTemplate() {
  if (templates[model.template].options && templates[model.template].options.withCountries) {
    var options = {};
    if (templates[model.template].options && templates[model.template].options.maxSeries) {
      options.max_selected_options = templates[model.template].options.maxSeries;
    }
    $('#countries') .show();
    $('#select_default_country')
      .chosen('destroy')
      .chosen(options);
  } else {
    $('#countries').hide();
  }
}

function updateSelects() {
  var tab = wizardConfig.tabs[wizardConfig.project.tabs[model.page]];
  model.template = tab.template.toString();
  if (model.charts) {
    // reset charts (page changed)
    model.charts = null;
  } else if (window.opener) {
    if (window.opener.template && window.opener.template === '7') {
      // selected chart in map template
      model.template = '7';
      if (window.opener.document) {
        var idx = $('.leaflet-control-layers-list label:has(input:checked)', window.opener.document).index();
        if (0 <= idx && idx < tab.charts.length) {
          model.charts = [tab.charts[idx]];
        }
      }
    } else if (window.opener.Chart && window.opener.Chart[0]) {
      var idx = tab.charts.indexOf(window.opener.Chart[0]);
      if (idx !== -1) {
        // selected chart in trends/ranking template
        model.charts = [tab.charts[idx]];
      }
      if (window.opener.template && window.opener.template === '6') {
        model.template = '6';
      }
    }
  }
  if (!model.charts) {
    model.charts = [tab.charts[0]];
  }

  var selectTemplate = $('#select_default_template');
  if (tab.altTemplate &&
      tab.altTemplate.length > 0) {
    selectTemplate.empty();
    tab.altTemplate.forEach(function(v) {
      $('<option>')
        .attr('value', v)
        .text(lang_labels.template[v] || templates[v].displayName)
        .appendTo(selectTemplate);
    });
    selectTemplate.val(model.template);
    selectTemplate.show();
  } else {
    selectTemplate.hide();
  }

  var selectIndicator = $('#select_default_indicator');
  selectIndicator.empty();
  tab.charts.forEach(function(v) {
    var titleText = $('<div>').html(wizardConfig.charts[v].title.en).text();
    $('<option>')
      .attr('value', v)
      .text(titleText)
      .appendTo(selectIndicator);
  });
  selectIndicator.val(model.charts[0]);
  updateTemplate();
}

function updateSettings() {
  var key = $(this).attr('id');
  var value = $(this).val();
  if (key == "select_default_country") {
    if (value == null) {
      return;
    }
    model.default_country = value;
  } else if (key == "select_size") {
    if (value == "custom") {
      $('#custom-size-fields').show();
      if ($('#customX').val() === '') {
        $('#customX').val(model.width);
        $('#customY').val(model.height);
      } else {
        model.width = $('#customX').val();
        model.height = $('#customY').val();
      }
    } else {
      $('#custom-size-fields').hide();
      model.width = sizes[value].width;
      model.height = sizes[value].height;
    }
  } else if (key == "select_lang") {
    model.lang = value;
  } else if (key == "custom-size-go") {
    model.width = $('#customX').val();
    model.height = $('#customY').val();
  } else if (key == "select_default_page") {
    model.page = value;
    model.embedLink = null;
    updateSelects();
  } else if (key == "select_default_template") {
    model.template = value;
    model.embedLink = null;
    updateTemplate();
  } else if (key == "select_default_indicator") {
    model.charts = [value];
    model.embedLink = null;
  }

  updateIframe();
}

function calcUrl(baseUrl) {
  if (model.embedLink != null) {
    return {
      url: baseUrl + model.embedLink,
      params: {
        lg: model.lang,
        cr: model.default_country
      }
    };
  } else {
    return {
      url: baseUrl + '/' + project,
      params: {
        cr: model.default_country,
        lg: model.lang,
        page: model.page,
        template: model.template,
        charts: model.charts.join(' ')
      }
    };
  }
}

function encodeAttr(s) {
  return '"' + s.toString().replace(/&/g, '&amp;').replace(/"/g, '&quot;') + '"';
}

function encodeParams(url, params) {
  var s = '?';
  $.each(params, function(k, v) {
    if ($.isArray(v)) {
      v = v.join(' ');
    }
    url += s + encodeURIComponent(k) + '=' + encodeURIComponent(v);
    s = '&';
  });
  return url.replace(/%20/g, '+');
}

function generateIframeCode(baseUrl) {
  var u = calcUrl(baseUrl);
  var url = encodeParams(u.url, u.params);
  return '<iframe width=' + encodeAttr(model.width) + ' height=' + encodeAttr(model.height) +
         ' frameBorder=' + encodeAttr(0) + ' src=' + encodeAttr(url) + '></iframe>';
}

function generateEmbedLink() {
  $("body").addClass("wait");
  $('#generate-embed').prop('disabled', true);
  $('#error').hide();
  $.ajax({
    type: 'POST',
    url: 'api-embed',
    dataType: 'json',
    data: JSON.stringify({
      project: project,
      cr: model.default_country,
      lg: model.lang,
      page: parseInt(model.page, 10),
      template: model.template,
      charts: model.charts
    })
  }).done(function(response) {
    $("body").removeClass("wait");
    $('#generate-embed').prop('disabled', false);
    if (response.error != null) {
      $('#errormsg').text(response.error);
      $('#error').show();
    } else {
      model.embedLink = response.url;
      updateIframe();
    }
  }).fail(function(jqXHR, textStatus, errorThrown) {
    $("body").removeClass("wait");
    $('#generate-embed').prop('disabled', false);
    $('#errormsg').text(jqXHR.status.toString() + ' ' + jqXHR.statusText);
    $('#error').show();
  });
}
