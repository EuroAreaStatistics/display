//JS script for embed.php

var default_country;
var default_year;

var title;
var subTitle;


var width = 420;
var height = 280;

var width1 = 420;
var height1 = 280;


$(function() {

        $(".countrySelect" ).prop('checked', false);
        $(".yearSelect" ).prop('checked', false);

        $('#select_lang option[value="'+lang+'"]').attr('selected',true);
        $('#select_template option[value="'+template+'"]').attr('selected',true);
        $('#select_color option[value="'+color+'"]').attr('selected',true);
        $('#select_chart option[value="'+chart+'"]').attr('selected',true);
                
        $('#sharecode').val(generateIframeCode(embedURL));
        $('#canvas-wrapper').html(generateIframeCode(baseURL));

        var links_abstand = (700 - width1) / 2;
        var oben_abstand = (100 - height1) / 2;
        $('#canvas-wrapper iframe').css('margin-left', links_abstand);

        $('#select_size option').first().html(width1+' x '+height1+' pixel');
        $('#select_size option').first().val(width1+'x'+height1);

        $('#customX').val(width1);
        $('#customY').val(height1);

        var selectedCountries = [];
        var selectedYears = [];
        
        $('#select_chart').change(function(){
          var value = $('#select_chart').val();
          $('#countryInput').empty();
          $('#yearInput').empty();
          var selectedCountriesNew = [];
          var selectedYearsNew = [];
          $.each(wizardConfig['charts'][value]['data']['keys'][0], function (k, v) {
            if ($.inArray(v,selectedCountries)>-1) {
              selectedCountriesNew.push(v);
            }
            $('#countryInput').append('<input class="countrySelect" type="checkbox" name="country" value="'+v+'"><span>'+v+'</span>');
          });
          $.each(wizardConfig['charts'][value]['data']['keys'][1], function (k, v) {
            if ($.inArray(v,selectedYears)>-1) {
              selectedYearsNew.push(v);
            }
            $('#yearInput').append('<input class="yearSelect" type="checkbox" name="year" value="'+v+'"><span>'+v+'</span>');
          });
          $.each(selectedCountriesNew, function (k, v) {
            $(".countrySelect[value='"+v+"']" ).prop('checked', true);
          });
          $.each(selectedYearsNew, function (k, v) {
            $(".yearSelect[value='"+v+"']" ).prop('checked', true);
          });
          updateIframe('countries',selectedCountriesNew)
          updateIframe('years',selectedYearsNew)
          selectedCountries = selectedCountriesNew;
          selectedYears = selectedYearsNew;
        });

        $('#sharetable').on('change','select',updateIframe);
        $('#sharetable #custom-size-go').click(updateIframe);
        $('#titleInput #custom-title-go').click(updateIframe);
        $('#subTitleInput #custom-subtitle-go').click(updateIframe);

        $('#countryInput').on('change','input',function(){
                var value = $(this).val();
                if($(this).is(":checked")) {
                        if ($.inArray(value,selectedCountries) < 0) {
                                selectedCountries.push(value);
                        }
                } else {
                        if ($.inArray(value,selectedCountries) > -1) {
                                selectedCountries.splice($.inArray(value,selectedCountries), 1);
                        }
                }
                updateIframe('countries',selectedCountries)
        });


        $('#yearInput').on('change','input',function(){
                var value = $(this).val();
                if($(this).is(":checked")) {
                        if ($.inArray(value,selectedYears) < 0) {
                                selectedYears.push(value);
                        }
                } else {
                        if ($.inArray(value,selectedYears) > -1) {
                                selectedYears.splice($.inArray(value,selectedYears), 1);
                        }
                }
                updateIframe('years',selectedYears)
        });

});

function updateIframe(key,selected) {
        if (key == "countries") {
                default_country = undefined;
                $.each(selected, function (k, v) {
                        if (default_country == undefined) {
                                default_country = v.toUpperCase();
                        } else {
                                default_country = default_country+'+'+v.toUpperCase();
                        }
                });
        } else if (key == "years") {
                default_year = undefined;
                $.each(selected, function (k, v) {
                        if (default_year == undefined) {
                                default_year = v;
                        } else {
                                default_year = default_year+'+'+v;
                        }
                });
        } else {
                key = $(this).attr('id');
                var value = $(this).val();
        }
        if (key == "select_size") {
            if (value == "custom") {
                if (!$('#custom-size-fields').is(':visible')) { $('#custom-size-fields').fadeIn(); }
                width = $('#customX').val();
                height = $('#customY').val();
            } else {
                if ($('#custom-size-fields').is(':visible')) { $('#custom-size-fields').fadeOut(); }
                var teile = value.split("x");
                width = teile[0];
                height = teile[1];
            }
        }
        else if (key == "select_lang") { lang = value; }
        else if (key == "select_template") { template = value; }
        else if (key == "select_color") { color = value; }
        else if (key == "select_chart") { chart = value; }
        else if (key == "custom-size-go") {
            width = $('#customX').val();
            height = $('#customY').val();
        }

        else if (key == "custom-title-go") {
          if ($('#customTitle').val() != '') {
            title = encodeURIComponent($('#customTitle').val());
          } else {
            title = 'undefined';
          }
        }

        else if (key == "custom-subtitle-go") {
          if ($('#customSubTitle').val() != '') {
            subTitle = encodeURIComponent($('#customSubTitle').val());
          } else {
            subTitle = 'undefined';
          }
        }

        $('#sharecode').val(generateIframeCode(embedURL));
        $('#canvas-wrapper').html(generateIframeCode(baseURL));

        var links_abstand = (700 - width) / 2;
        var oben_abstand = (100 - height) / 2;
        $('#canvas-wrapper iframe').css('margin-left', links_abstand);
}
		
function generateIframeCode(base_url) {

  var output = "<iframe width='"+
                width+"' height='"+
                height+"' frameBorder='0' "+
                "src='"+base_url+
                        "/"+project+
                        "?yr="+default_year+
                        "&lc="+default_country+
                        "&lg="+lang+
                        "&template="+template+
                        "&color="+color+
                        "&chart="+chart+
                        "&title="+title+
                        "&subtitle="+subTitle+
                        "'></iframe>";
//  encodeURL (encodeURIComponent(output));

  return output ;

};


function encodeURL (urlstring) {
  $.ajax({
    dataType: "text",
    url: baseURL+"/simpleurl?url="+urlstring,
    success: ajaxSuccess,
    error: function (xhr, ajaxOptions, thrownError) {
      if (xhr.status == '404') {
        alert ('No data available.')};
    }
  });

  function ajaxSuccess(data) {
    if (data != null) {
      console.log(data);
    } else {
      alert('No data available.');
    }
  }

}


