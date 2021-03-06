function downloadHandler(type, name) {
  if (window.navigator.msSaveOrOpenBlob) return function(data) {
    $("body").removeClass("wait");
    blob = new Blob([data], { type: 'text/csv' });
    window.navigator.msSaveOrOpenBlob(blob, name);
  };
  if (('download' in document.createElement('a')) && URL.createObjectURL != null) return function(data) {
    $("body").removeClass("wait");
    $("#downloadLink").each(function() {
      URL.revokeObjectURL($(this).attr('href'));
    });
    $("#downloadLink").remove();
    var blob = new Blob([data], { type: 'text/csv' });
    var url = URL.createObjectURL(blob);
    var link = $('<a id="downloadLink" style="display:none">')
      .attr('href', url)
      .attr('download', name)
      .appendTo('body');
    link[0].click();
  };
  if ('download' in document.createElement('a')) return function(data) {
    $("body").removeClass("wait");
    var link = $('<a style="display:none">')
      .attr('href', 'data:'+type+','+encodeURIComponent(data))
      .attr('download', name)
      .appendTo('body');
    link[0].click();
    link.remove();
  };
  return function(data) {
    $("body").removeClass("wait");
    var iframe = $('<iframe style="display:none">')
      .appendTo('body');
    var doc = iframe[0].contentDocument || iframe[0].contentWindow.document;
    doc.open(type, 'replace');
    doc.write(data);
    doc.close();
    doc.execCommand('SaveAs', true, name);
  };
}

function downloadError(jqXHR, textStatus, errorThrown) {
  $("body").removeClass("wait");
  var error = errorThrown || textStatus || 'unknown AJAX error';
  if (jqXHR.getResponseHeader('Content-type')==='application/json') {
    try {
      error = JSON.parse(jqXHR.responseText).error;
    } catch (e) {} // ignore errors
  } else if (error == "error") {
    error = "error fetching URL, see javascript console";
  }
  alert(error);
}

$(function() {
 $(".downloadPage a[data-type]").click(function (event) {
   event.preventDefault();
   var mimeType = $(this).attr('data-type');
   $("body").addClass("wait");
   $.ajax({
     url: $(this).attr('href'),
     dataType: 'text',
     accepts: {text: mimeType},
     error: downloadError,
     success: downloadHandler(mimeType, $(this).attr('download'))
   });
 });
});
