//JS script for embed.php

var windowWidth = "innerWidth" in window
               ? window.innerWidth
               : document.documentElement.offsetWidth;


$(function() {

        $('#select_default_country option[value="'+default_country+'"]').attr('selected',true);
        $('#select_default_country').chosen();
        $('#select_lang option[value="'+lang+'"]').attr('selected',true);
                
        $('#sharecode').val(generateIframeCode(embedURL));
        $('#canvas-wrapper').html(generateIframeCode(baseURL));
	$('#canvas-wrapper iframe').css('margin', '0 auto').css('display', 'block');

        $('#select_size option').first().html(width1+' x '+height1+' pixel');
        $('#select_size option').first().val(width1+'x'+height1);

        $('#customX').val(width1);
        $('#customY').val(height1);
        
        $('#sharetable select').change(updateIframe);
        $('#sharetable #custom-size-go').click(updateIframe);

});

function updateIframe() {
        var key = $(this).attr('id');
        var value = $(this).val();
        if (key == "select_default_country") { default_country = value; }
        else if (key == "select_size") {
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
        else if (key == "select_country") { }
        else if (key == "custom-size-go") {
            width = $('#customX').val();
            height = $('#customY').val();
        }

        if (default_country != null) {
            $('#sharecode').val(generateIframeCode(embedURL));
            $('#canvas-wrapper').html(generateIframeCode(baseURL));
	    $('#canvas-wrapper iframe').css('margin', '0 auto').css('display', 'block');
        }
}
		
function generateIframeCode(base_url) {
    var cr = default_country;
    if ($.isArray(cr)) {
      cr = cr.join('+');
    }
    var output = "<iframe width='"+width+"' height='"+height+
              "' frameBorder='0' src='"+base_url+
              "/"+project+
              "?cr="+cr+
              "&lg="+lang+
              "&page="+page+
              "'></iframe>";
    return output;
};
